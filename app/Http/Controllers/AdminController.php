<?php
namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Invoice;
use App\Models\Paket;
use App\Models\Odp;
use App\Models\Odc;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'total_pelanggan' => Pelanggan::count(),
            'pelanggan_aktif' => Pelanggan::where('status', 'aktif')->count(),
            'pelanggan_isolir' => Pelanggan::where('status', 'isolir')->count(),
            'total_invoice'   => Invoice::count(),
            'invoice_unpaid'  => Invoice::where('status', 'unpaid')->count(),
            'invoice_paid'    => Invoice::where('status', 'paid')->count(),
            'total_paket'     => Paket::count(),
            'total_odp'       => Odp::count(),
            'total_odc'       => Odc::count(),
        ];

        return view('admin.dashboard', $data);
    }
}