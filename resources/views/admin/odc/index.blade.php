@extends('layouts.app')
@section('title', 'Manajemen ODC')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">Manajemen ODC</h2>
    <a href="{{ route('odc.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        + Tambah ODC
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
                <th class="px-5 py-3 font-medium">Kode ODC</th>
                <th class="px-5 py-3 font-medium">Nama ODC</th>
                <th class="px-5 py-3 font-medium">Jumlah Port</th>
                <th class="px-5 py-3 font-medium">Jumlah ODP</th>
                <th class="px-5 py-3 font-medium">Koordinat</th>
                <th class="px-5 py-3 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($odc as $o)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-mono text-xs text-gray-800">{{ $o->kode_odc }}</td>
                <td class="px-5 py-3 text-gray-800">{{ $o->nama_odc }}</td>
                <td class="px-5 py-3 text-gray-700">{{ $o->jumlah_port }}</td>
                <td class="px-5 py-3 text-gray-700">{{ $o->odp_count }}</td>
                <td class="px-5 py-3 font-mono text-xs text-gray-500">
                    @if($o->latitude && $o->longitude)
                        {{ $o->latitude }}, {{ $o->longitude }}
                    @else
                        <span class="text-gray-300">—</span>
                    @endif
                </td>
                <td class="px-5 py-3 flex gap-2">
                    <a href="{{ route('odc.edit', $o) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('odc.destroy', $o) }}"
                        onsubmit="return confirm('Hapus ODC ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada ODC.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection