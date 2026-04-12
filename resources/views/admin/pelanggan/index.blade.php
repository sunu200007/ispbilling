@extends('layouts.app')
@section('title', 'Manajemen Pelanggan')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">Manajemen Pelanggan</h2>
    <a href="{{ route('pelanggan.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        + Tambah Pelanggan
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 text-green-700 text-sm rounded-lg px-4 py-3 mb-4">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-5 py-3 font-medium">Nama</th>
                <th class="px-5 py-3 font-medium">Username</th>
                <th class="px-5 py-3 font-medium">Paket</th>
                <th class="px-5 py-3 font-medium">Pool</th>
                <th class="px-5 py-3 font-medium">ODP</th>
                <th class="px-5 py-3 font-medium">Jatuh Tempo</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pelanggan as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-medium text-gray-800">{{ $p->nama }}</td>
                <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $p->username }}</td>
                <td class="px-5 py-3 text-gray-700">{{ $p->paket->nama_paket }}</td>
                <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $p->ipPool->nama_pool ?? '—' }}</td>
                <td class="px-5 py-3 text-xs text-gray-600">{{ $p->odp->kode_odp ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-700">{{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
                <td class="px-5 py-3">
                    @if($p->status === 'aktif')
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Aktif</span>
                    @elseif($p->status === 'isolir')
                        <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">Isolir</span>
                    @else
                        <span class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded-full">Nonaktif</span>
                    @endif
                </td>
                <td class="px-5 py-3 flex gap-2">
                    <a href="{{ route('pelanggan.show', $p) }}" class="text-green-600 hover:underline text-xs">Detail</a>
                    <a href="{{ route('pelanggan.edit', $p) }}" class="text-blue-600 hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('pelanggan.destroy', $p) }}"
                        onsubmit="return confirm('Hapus pelanggan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-8 text-center text-gray-400">Belum ada pelanggan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection