<?php
namespace App\Http\Controllers;

use App\Models\IpPool;
use App\Models\Paket;
use Illuminate\Http\Request;

class IpPoolController extends Controller
{
    public function index()
    {
        $pools = IpPool::with('paket')->latest()->get();
        return view('admin.ip_pool.index', compact('pools'));
    }

    public function create()
    {
        $paket = Paket::where('status', 'aktif')->get();
        return view('admin.ip_pool.create', compact('paket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'paket_id' => 'required|exists:paket,id',
            'network'  => 'required|ip',
            'prefix'   => 'required|integer|min:24|max:30',
        ]);

        $kapasitas = $this->hitungKapasitas($request->prefix);
        $ips       = $this->hitungIpRange($request->network, $request->prefix);
        $paket     = Paket::find($request->paket_id);
        $urutan    = IpPool::where('paket_id', $request->paket_id)->count() + 1;
        $namaPool  = 'pool-' . str()->slug($paket->nama_paket) . '-' . $urutan;

        DB::transaction(function () use ($request, $namaPool, $kapasitas, $ips, $paket) {
            $pool = IpPool::create([
                'paket_id'  => $request->paket_id,
                'nama_pool' => $namaPool,
                'network'   => $request->network,
                'prefix'    => $request->prefix,
                'ip_start'  => $ips['start'],
                'ip_end'    => $ips['end'],
                'kapasitas' => $kapasitas,
                'status'    => 'aktif',
            ]);

            // Populate radippool dengan semua IP dalam range
            $this->populateRadippool($namaPool, $request->network, $request->prefix);
        });

        return redirect()->route('ip-pool.index')->with('success', 'IP Pool berhasil ditambahkan dan IP telah dipopulate ke RADIUS.');
    }

    private function populateRadippool(string $namaPool, string $network, int $prefix)
    {
        $ipLong   = ip2long($network);
        $jumlahIp = (int) pow(2, 32 - $prefix);
        $records  = [];

        for ($i = 0; $i < $jumlahIp; $i++) {
            $records[] = [
                'pool_name'       => $namaPool,
                'framedipaddress' => long2ip($ipLong + $i),
                'nasipaddress'    => '',
                'calledstationid' => '',
                'callingstationid'=> '',
                'expiry_time'     => null,
                'username'        => '',
                'pool_key'        => '',
            ];
        }

        DB::connection('radius')->table('radippool')->insert($records);
    }

    public function destroy(IpPool $ipPool)
    {
        if ($ipPool->terpakai > 0) {
            return back()->with('error', 'Pool tidak bisa dihapus, masih ada pelanggan aktif.');
        }

        DB::transaction(function () use ($ipPool) {
            DB::connection('radius')->table('radippool')
                ->where('pool_name', $ipPool->nama_pool)
                ->delete();
            $ipPool->delete();
        });

        return redirect()->route('ip-pool.index')->with('success', 'IP Pool berhasil dihapus.');
    }

    private function hitungKapasitas(int $prefix): int
    {
        return (int) pow(2, 32 - $prefix);
    }

    private function hitungIpRange(string $network, int $prefix): array
    {
        $ipLong  = ip2long($network);
        $ipStart = long2ip($ipLong);
        $ipEnd   = long2ip($ipLong + pow(2, 32 - $prefix) - 1);
        return ['start' => $ipStart, 'end' => $ipEnd];
    }
}