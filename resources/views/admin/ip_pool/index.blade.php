@extends('layouts.app')
@section('title', 'IP Pool')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">Manajemen IP Pool</h2>
    <a href="{{ route('ip-pool.create') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
        + Tambah Pool
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
                <th class="px-5 py-3 font-medium">Nama Pool</th>
                <th class="px-5 py-3 font-medium">Paket</th>
                <th class="px-5 py-3 font-medium">Network</th>
                <th class="px-5 py-3 font-medium">Range IP</th>
                <th class="px-5 py-3 font-medium">Kapasitas</th>
                <th class="px-5 py-3 font-medium">Terpakai</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pools as $pool)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-mono text-xs text-gray-800">{{ $pool->nama_pool }}</td>
                <td class="px-5 py-3 text-gray-700">{{ $pool->paket->nama_paket }}</td>
                <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $pool->network }}/{{ $pool->prefix }}</td>
                <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $pool->ip_start }} - {{ $pool->ip_end }}</td>
                <td class="px-5 py-3 text-gray-700">{{ $pool->kapasitas }}</td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-2">
                        <div class="flex-1 bg-gray-100 rounded-full h-2 w-20">
                            <div class="h-2 rounded-full {{ $pool->is_penuh ? 'bg-red-500' : 'bg-green-500' }}"
                                style="width: {{ $pool->kapasitas > 0 ? min(100, round($pool->terpakai / $pool->kapasitas * 100)) : 0 }}%">
                            </div>
                        </div>
                        <span class="text-xs text-gray-600">{{ $pool->terpakai }}/{{ $pool->kapasitas }}</span>
                    </div>
                </td>
                <td class="px-5 py-3">
                    @if($pool->status === 'aktif' && !$pool->is_penuh)
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Aktif</span>
                    @elseif($pool->is_penuh)
                        <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">Penuh</span>
                    @else
                        <span class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded-full">Nonaktif</span>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <form method="POST" action="{{ route('ip-pool.destroy', $pool) }}"
                        onsubmit="return confirm('Hapus pool ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-5 py-8 text-center text-gray-400">Belum ada IP Pool.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection