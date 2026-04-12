<?php
namespace App\Http\Controllers;

use App\Models\Odp;
use App\Models\Odc;
use Illuminate\Http\Request;

class OdpController extends Controller
{
    public function index()
    {
        $odp = Odp::with('odc')->withCount('pelanggan')->latest()->get();
        return view('admin.odp.index', compact('odp'));
    }

    public function create()
    {
        $odc = Odc::all();
        return view('admin.odp.create', compact('odc'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_odp'   => 'required|string|max:100',
            'kode_odp'   => 'required|string|max:50|unique:odp',
            'odc_id'     => 'required|exists:odc,id',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
            'jumlah_port'=> 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        Odp::create($request->all());
        return redirect()->route('odp.index')->with('success', 'ODP berhasil ditambahkan.');
    }

    public function edit(Odp $odp)
    {
        $odc = Odc::all();
        return view('admin.odp.edit', compact('odp', 'odc'));
    }

    public function update(Request $request, Odp $odp)
    {
        $request->validate([
            'nama_odp'   => 'required|string|max:100',
            'kode_odp'   => 'required|string|max:50|unique:odp,kode_odp,'.$odp->id,
            'odc_id'     => 'required|exists:odc,id',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
            'jumlah_port'=> 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $odp->update($request->all());
        return redirect()->route('odp.index')->with('success', 'ODP berhasil diupdate.');
    }

    public function destroy(Odp $odp)
    {
        if ($odp->pelanggan()->count() > 0) {
            return back()->with('error', 'ODP tidak bisa dihapus, masih ada pelanggan terhubung.');
        }
        $odp->delete();
        return redirect()->route('odp.index')->with('success', 'ODP berhasil dihapus.');
    }
}