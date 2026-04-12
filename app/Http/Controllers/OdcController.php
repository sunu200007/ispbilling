<?php
namespace App\Http\Controllers;

use App\Models\Odc;
use Illuminate\Http\Request;

class OdcController extends Controller
{
    public function index()
    {
        $odc = Odc::withCount('odp')->latest()->get();
        return view('admin.odc.index', compact('odc'));
    }

    public function create()
    {
        return view('admin.odc.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_odc'   => 'required|string|max:100',
            'kode_odc'   => 'required|string|max:50|unique:odc',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
            'jumlah_port'=> 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        Odc::create($request->all());
        return redirect()->route('odc.index')->with('success', 'ODC berhasil ditambahkan.');
    }

    public function edit(Odc $odc)
    {
        return view('admin.odc.edit', compact('odc'));
    }

    public function update(Request $request, Odc $odc)
    {
        $request->validate([
            'nama_odc'   => 'required|string|max:100',
            'kode_odc'   => 'required|string|max:50|unique:odc,kode_odc,'.$odc->id,
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
            'jumlah_port'=> 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $odc->update($request->all());
        return redirect()->route('odc.index')->with('success', 'ODC berhasil diupdate.');
    }

    public function destroy(Odc $odc)
    {
        if ($odc->odp()->count() > 0) {
            return back()->with('error', 'ODC tidak bisa dihapus, masih ada ODP terhubung.');
        }
        $odc->delete();
        return redirect()->route('odc.index')->with('success', 'ODC berhasil dihapus.');
    }
}