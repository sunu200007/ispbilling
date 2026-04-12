<?php
namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Paket;
use App\Models\Odp;
use App\Models\IpPool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::with(['paket', 'odp', 'ipPool'])->latest()->get();
        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        $paket  = Paket::where('status', 'aktif')->get();
        $odp    = Odp::with('odc')->get();
        return view('admin.pelanggan.create', compact('paket', 'odp'));
    }

    public function getPools(Request $request)
    {
        $pools = IpPool::where('paket_id', $request->paket_id)
            ->where('status', '!=', 'nonaktif')
            ->get()
            ->map(function ($pool) {
                return [
                    'id'        => $pool->id,
                    'nama_pool' => $pool->nama_pool,
                    'sisa'      => $pool->sisa,
                    'kapasitas' => $pool->kapasitas,
                    'penuh'     => $pool->is_penuh,
                ];
            });
        return response()->json($pools);
    }

    public function store(Request $request)
{
    $request->validate([
        'nama'                => 'required|string|max:100',
        'username'            => 'required|string|max:50|unique:pelanggan',
        'password'            => 'required|string|min:6',
        'no_hp'               => 'nullable|string|max:20',
        'alamat'              => 'nullable|string',
        'paket_id'            => 'required|exists:paket,id',
        'ip_pool_id'          => 'required|exists:ip_pool,id',
        'odp_id'              => 'required|exists:odp,id',
        'latitude'            => 'nullable|numeric',
        'longitude'           => 'nullable|numeric',
        'tanggal_aktif'       => 'required|date',
        'tanggal_jatuh_tempo' => 'required|date',
        'status'              => 'required|in:aktif,nonaktif,isolir',
    ]);

    $pool = IpPool::findOrFail($request->ip_pool_id);

    if ($pool->is_penuh) {
        return back()->withErrors(['ip_pool_id' => 'Pool sudah penuh, pilih pool lain.'])->withInput();
    }

    $ipAddress = $pool->getAvailableIp();
    if (!$ipAddress) {
        return back()->withErrors(['ip_pool_id' => 'Tidak ada IP tersedia di pool ini.'])->withInput();
    }

    DB::transaction(function () use ($request, $pool, $ipAddress) {
        $pelanggan = Pelanggan::create([
            'nama'                => $request->nama,
            'username'            => $request->username,
            'password'            => Hash::make($request->password),
            'no_hp'               => $request->no_hp,
            'alamat'              => $request->alamat,
            'paket_id'            => $request->paket_id,
            'ip_pool_id'          => $request->ip_pool_id,
            'ip_address'          => $ipAddress,
            'odp_id'              => $request->odp_id,
            'latitude'            => $request->latitude,
            'longitude'           => $request->longitude,
            'tanggal_aktif'       => $request->tanggal_aktif,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'status'              => $request->status,
        ]);

        $this->syncRadiusAdd($pelanggan, $request->password, $pool);

        if ($pool->fresh()->is_penuh) {
            $pool->update(['status' => 'penuh']);
        }
    });

    return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
}

    public function show(Pelanggan $pelanggan)
    {
        $pelanggan->load(['paket', 'odp.odc', 'ipPool', 'invoice']);
        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        $paket = Paket::where('status', 'aktif')->get();
        $odp   = Odp::with('odc')->get();
        $pools = IpPool::where('paket_id', $pelanggan->paket_id)->get();
        return view('admin.pelanggan.edit', compact('pelanggan', 'paket', 'odp', 'pools'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'nama'               => 'required|string|max:100',
            'username'           => 'required|string|max:50|unique:pelanggan,username,'.$pelanggan->id,
            'no_hp'              => 'nullable|string|max:20',
            'alamat'             => 'nullable|string',
            'paket_id'           => 'required|exists:paket,id',
            'ip_pool_id'         => 'required|exists:ip_pool,id',
            'odp_id'             => 'required|exists:odp,id',
            'latitude'           => 'nullable|numeric',
            'longitude'          => 'nullable|numeric',
            'tanggal_aktif'      => 'required|date',
            'tanggal_jatuh_tempo'=> 'required|date',
            'status'             => 'required|in:aktif,nonaktif,isolir',
        ]);

        DB::transaction(function () use ($request, $pelanggan) {
            $oldUsername = $pelanggan->username;

            $pelanggan->update([
                'nama'                => $request->nama,
                'username'            => $request->username,
                'no_hp'               => $request->no_hp,
                'alamat'              => $request->alamat,
                'paket_id'            => $request->paket_id,
                'ip_pool_id'          => $request->ip_pool_id,
                'odp_id'              => $request->odp_id,
                'latitude'            => $request->latitude,
                'longitude'           => $request->longitude,
                'tanggal_aktif'       => $request->tanggal_aktif,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                'status'              => $request->status,
            ]);

            if ($request->filled('password')) {
                $pelanggan->update(['password' => Hash::make($request->password)]);
                $this->syncRadiusUpdatePassword($request->username, $request->password);
            }

            // Update status di RADIUS jika berubah
            $this->syncRadiusUpdateStatus($pelanggan);
        });

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diupdate.');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        DB::transaction(function () use ($pelanggan) {
            $pool = $pelanggan->ipPool;
            $this->syncRadiusDelete($pelanggan->username);
            $pelanggan->delete();

            // Update status pool jika tidak penuh lagi
            if ($pool && $pool->status === 'penuh') {
                $pool->update(['status' => 'aktif']);
            }
        });

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }

    // ===== RADIUS SYNC =====

    private function syncRadiusAdd(Pelanggan $pelanggan, string $plainPassword, IpPool $pool)
    {
        DB::connection('radius')->table('radcheck')->insert([
            ['username' => $pelanggan->username, 'attribute' => 'Cleartext-Password', 'op' => ':=', 'value' => $plainPassword],
        ]);

        DB::connection('radius')->table('radreply')->insert([
            ['username' => $pelanggan->username, 'attribute' => 'Framed-IP-Address', 'op' => ':=', 'value' => $pelanggan->ip_address],
        ]);

        DB::connection('radius')->table('radusergroup')->insert([
            ['username' => $pelanggan->username, 'groupname' => 'paket-'.$pelanggan->paket_id, 'priority' => 1],
        ]);
    }

    private function syncRadiusUpdatePassword(string $username, string $plainPassword)
    {
        DB::connection('radius')->table('radcheck')
            ->where('username', $username)
            ->where('attribute', 'Cleartext-Password')
            ->update(['value' => $plainPassword]);
    }

    private function syncRadiusUpdateStatus(Pelanggan $pelanggan)
    {
        if ($pelanggan->status === 'isolir') {
            // Tambah Auth-Type := Reject di radcheck
            $exists = DB::connection('radius')->table('radcheck')
                ->where('username', $pelanggan->username)
                ->where('attribute', 'Auth-Type')
                ->exists();
            if (!$exists) {
                DB::connection('radius')->table('radcheck')->insert([
                    ['username' => $pelanggan->username, 'attribute' => 'Auth-Type', 'op' => ':=', 'value' => 'Reject'],
                ]);
            }
        } else {
            // Hapus Auth-Type Reject
            DB::connection('radius')->table('radcheck')
                ->where('username', $pelanggan->username)
                ->where('attribute', 'Auth-Type')
                ->delete();
        }
    }

    private function syncRadiusDelete(string $username)
    {
        DB::connection('radius')->table('radcheck')->where('username', $username)->delete();
        DB::connection('radius')->table('radreply')->where('username', $username)->delete();
        DB::connection('radius')->table('radusergroup')->where('username', $username)->delete();
    }
}