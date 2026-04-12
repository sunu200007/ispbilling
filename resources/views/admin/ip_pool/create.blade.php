@extends('layouts.app')
@section('title', 'Tambah IP Pool')
@section('content')

<div class="max-w-xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah IP Pool</h2>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('ip-pool.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Paket</label>
                <select name="paket_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Pilih Paket --</option>
                    @foreach($paket as $p)
                        <option value="{{ $p->id }}" {{ old('paket_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_paket }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Network Address</label>
                <input type="text" name="network" value="{{ old('network') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="192.168.1.0" required>
                <p class="text-xs text-gray-400 mt-1">Masukkan network address, bukan IP pertama</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Prefix (CIDR)</label>
                <select name="prefix"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="29">/29 — 8 IP</option>
                    <option value="28">/28 — 16 IP</option>
                    <option value="27">/27 — 32 IP</option>
                    <option value="26">/26 — 64 IP</option>
                    <option value="25">/25 — 128 IP</option>
                    <option value="24">/24 — 256 IP</option>
                </select>
            </div>

            <div class="bg-blue-50 rounded-lg px-4 py-3 mb-6 text-xs text-blue-700 font-mono">
                Nama pool akan digenerate otomatis, contoh: <strong>pool-infinitelite-1</strong>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Simpan
                </button>
                <a href="{{ route('ip-pool.index') }}"
                    class="text-gray-500 hover:text-gray-700 text-sm px-5 py-2 rounded-lg border border-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection