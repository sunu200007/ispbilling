@extends('layouts.app')

@section('title', 'Manajemen Paket')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">Manajemen Paket</h2>
    <a href="{{ route('paket.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        + Tambah Paket
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 text-green-700 text-sm rounded-lg px-4 py-3 mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-5 py-3 font-medium">Nama Paket</th>
                <th class="px-5 py-3 font-medium">Pool Name</th>
                <th class="px-5 py-3 font-medium">Harga</th>
                <th class="px-5 py-3 font-medium">Download</th>
                <th class="px-5 py-3 font-medium">Upload</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($paket as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-medium text-gray-800">{{ $p->nama_paket }}</td>
                <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $p->pool_name }}</td>
                <td class="px-5 py-3 text-gray-700">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                <td class="px-5 py-3 text-gray-700">{{ $p->kecepatan_download }} Mbps</td>
                <td class="px-5 py-3 text-gray-700">{{ $p->kecepatan_upload }} Mbps</td>
                <td class="px-5 py-3">
                    @if($p->status === 'aktif')
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Aktif</span>
                    @else
                        <span class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded-full">Nonaktif</span>
                    @endif
                </td>
                <td class="px-5 py-3 flex gap-2">
                    <a href="{{ route('paket.edit', $p) }}"
                        class="text-blue-600 hover:underline text-xs">Edit</a>
                    <form method="POST" action="{{ route('paket.destroy', $p) }}"
                        onsubmit="return confirm('Hapus paket ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-8 text-center text-gray-400">Belum ada paket.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection