@extends('layouts.app')

@section('title', 'Edit Paket')

@section('content')
<div class="max-w-xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Paket</h2>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('paket.update', $paket) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Nama Paket</label>
                <input type="text" name="nama_paket" value="{{ old('nama_paket', $paket->nama_paket) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga', $paket->harga) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Download (Mbps)</label>
                    <input type="number" name="kecepatan_download" value="{{ old('kecepatan_download', $paket->kecepatan_download) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Upload (Mbps)</label>
                    <input type="number" name="kecepatan_upload" value="{{ old('kecepatan_upload', $paket->kecepatan_upload) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1">Status</label>
                <select name="status"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="aktif" {{ $paket->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $paket->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Update
                </button>
                <a href="{{ route('paket.index') }}"
                    class="text-gray-500 hover:text-gray-700 text-sm px-5 py-2 rounded-lg border border-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection