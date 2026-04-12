<?php
namespace App\Http\Controllers;

use App\Models\Paket;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $paket = Paket::latest()->get();
        return view('admin.paket.index', compact('paket'));
    }

    public function create()
    {
        return view('admin.paket.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_paket'         => 'required|string|max:100',
            'harga'              => 'required|integer|min:0',
            'kecepatan_download' => 'required|integer|min:1',
            'kecepatan_upload'   => 'required|integer|min:1',
            'status'             => 'required|in:aktif,nonaktif',
        ]);

        Paket::create($request->all());

        return redirect()->route('paket.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Paket $paket)
    {
        return view('admin.paket.edit', compact('paket'));
    }

    public function update(Request $request, Paket $paket)
    {
        $request->validate([
                'nama_paket'         => 'required|string|max:100',
                'harga'              => 'required|integer|min:0',
                'kecepatan_download' => 'required|integer|min:1',
                'kecepatan_upload'   => 'required|integer|min:1',
                'status'             => 'required|in:aktif,nonaktif',
            ]);

        $paket->update($request->all());

        return redirect()->route('paket.index')->with('success', 'Paket berhasil diupdate.');
    }

    public function destroy(Paket $paket)
    {
        $paket->delete();
        return redirect()->route('paket.index')->with('success', 'Paket berhasil dihapus.');
    }
}