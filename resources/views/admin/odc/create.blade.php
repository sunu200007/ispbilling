@extends('layouts.app')
@section('title', 'Tambah ODC')
@section('content')

<div class="max-w-xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah ODC</h2>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('odc.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Kode ODC</label>
                <input type="text" name="kode_odc" value="{{ old('kode_odc') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="ODC-A1" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Nama ODC</label>
                <input type="text" name="nama_odc" value="{{ old('nama_odc') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="ODC Area Selatan" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Jumlah Port</label>
                <input type="number" name="jumlah_port" value="{{ old('jumlah_port', 8) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Latitude</label>
                    <input type="text" name="latitude" value="{{ old('latitude') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="-7.123456">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Longitude</label>
                    <input type="text" name="longitude" value="{{ old('longitude') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="110.123456">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="2"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Opsional...">{{ old('keterangan') }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Simpan
                </button>
                <a href="{{ route('odc.index') }}"
                    class="text-gray-500 hover:text-gray-700 text-sm px-5 py-2 rounded-lg border border-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection