@extends('layouts.app')
@section('title', 'Edit ODP')
@section('content')

<div class="max-w-xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Edit ODP</h2>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('odp.update', $odp) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Kode ODP</label>
                <input type="text" name="kode_odp" value="{{ old('kode_odp', $odp->kode_odp) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Nama ODP</label>
                <input type="text" name="nama_odp" value="{{ old('nama_odp', $odp->nama_odp) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">ODC</label>
                <select name="odc_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @foreach($odc as $o)
                        <option value="{{ $o->id }}" {{ old('odc_id', $odp->odc_id) == $o->id ? 'selected' : '' }}>
                            {{ $o->kode_odc }} — {{ $o->nama_odc }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Jumlah Port</label>
                <input type="number" name="jumlah_port" value="{{ old('jumlah_port', $odp->jumlah_port) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Latitude</label>
                    <input type="text" name="latitude" value="{{ old('latitude', $odp->latitude) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Longitude</label>
                    <input type="text" name="longitude" value="{{ old('longitude', $odp->longitude) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="2"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('keterangan', $odp->keterangan) }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Update
                </button>
                <a href="{{ route('odp.index') }}"
                    class="text-gray-500 hover:text-gray-700 text-sm px-5 py-2 rounded-lg border border-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection