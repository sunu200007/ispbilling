@extends('layouts.app')
@section('title', 'Manajemen ODP')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">Manajemen ODP</h2>
    <a href="{{ route('odp.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        + Tambah ODP
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 text-green-700 text-sm rounded-lg px-4 py-3 mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-50 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">{{ session('error') }}</div>
@endif

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-5 py-3 font-medium">Kode ODP</th>
                <th class="px-5 py-3 font-medium">Nama ODP</th>
                <th class="px-5 py-3 font-medium">ODC</th>
                <th class="px-5 py-3 font-medium">Port</th>
                <th class="px-5 py-3 font-medium">Pelanggan</th>
                <th class="px-5 py-3 font-medium">Koordinat</th>
                <th class="px-5 py-3 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($odp as $o)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-mono text-xs text-gray-800">{{ $o->kode_odp }}</td>
                <td class="px-5 py-3 text-gray-800">{{ $o->nama_odp }}</td>
                <td class="px-5 py-3 text-gray-600 text-xs">{{ $o->odc->kode_odc }}</td>
                <td class="px-5 py-3 text-gray-700">{{ $o->jumlah_port }}</td>
                <td class="px-5 py-3 text-gray-700">{{ $o->pelanggan_count }}</td>
                <td class="px-5 py-3 font-mono text-xs text-gray-500">
                    @if($o->latitude && $o->longitude)
                        {{ $o->latitude }}, {{ $o->longitude }}
                    @else
                        <span class="text-gray-300">—</span>
                    @endif
                </td>
                <td class="px-5 py-3 flex gap-2">
                    <a href="{{ route('odp.edit', $o) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('odp.destroy', $o) }}"
                        onsubmit="return confirm('Hapus ODP ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-8 text-center text-gray-400">Belum ada ODP.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection