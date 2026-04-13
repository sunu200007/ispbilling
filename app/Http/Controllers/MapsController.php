<?php
namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Odp;
use App\Models\Odc;
use Illuminate\Support\Facades\DB;

class MapsController extends Controller
{
    public function index()
    {
        return view('admin.maps.index');
    }

    public function getData()
    {
        // Ambil session aktif dari radacct
        $activeSessions = DB::connection('radius')->table('radacct')
            ->whereNull('acctstoptime')
            ->pluck('username')
            ->toArray();

        // Pelanggan
        $pelanggan = Pelanggan::with(['paket', 'odp', 'ipPool'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($p) use ($activeSessions) {
                return [
                    'type'       => 'pelanggan',
                    'id'         => $p->id,
                    'nama'       => $p->nama,
                    'username'   => $p->username,
                    'ip'         => $p->ip_address,
                    'paket'      => $p->paket->nama_paket ?? '-',
                    'odp'        => $p->odp->kode_odp ?? '-',
                    'status'     => $p->status,
                    'online'     => in_array($p->username, $activeSessions),
                    'lat'        => (float) $p->latitude,
                    'lng'        => (float) $p->longitude,
                ];
            });

        // ODP
        $odp = Odp::with('odc')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($o) {
                return [
                    'type'     => 'odp',
                    'id'       => $o->id,
                    'nama'     => $o->nama_odp,
                    'kode'     => $o->kode_odp,
                    'odc'      => $o->odc->kode_odc ?? '-',
                    'port'     => $o->jumlah_port,
                    'lat'      => (float) $o->latitude,
                    'lng'      => (float) $o->longitude,
                ];
            });

        // ODC
        $odc = Odc::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($o) {
                return [
                    'type' => 'odc',
                    'id'   => $o->id,
                    'nama' => $o->nama_odc,
                    'kode' => $o->kode_odc,
                    'port' => $o->jumlah_port,
                    'lat'  => (float) $o->latitude,
                    'lng'  => (float) $o->longitude,
                ];
            });

        return response()->json([
            'pelanggan' => $pelanggan,
            'odp'       => $odp,
            'odc'       => $odc,
        ]);
    }
}
