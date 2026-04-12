@extends('layouts.app')
@section('title', 'Detail Pelanggan')
@section('content')

<div class="max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Detail Pelanggan</h2>
        <div class="flex gap-2">
            <a href="{{ route('pelanggan.edit', $pelanggan) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                Edit
            </a>
            <a href="{{ route('pelanggan.index') }}"
                class="text-gray-500 text-sm px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3">Data Pelanggan</p>
        <table class="w-full text-sm">
            <tr class="border-b border-gray-100">
                <td class="py-2 text-gray-500 w-40">Nama</td>
                <td class="py-2 font-medium text-gray-800">{{ $pelanggan->nama }}</td>
            </tr>
            <tr class="border-b border-gray-100">
                <td class="py-2 text-gray-500">No. HP</td>
                <td class="py-2 text-gray-700">{{ $pelanggan->no_hp ?? '—' }}</td>
            </tr>
            <tr class="border-b border-gray-100">
                <td class="py-2 text-gray-500">Alamat</td>
                <td class="py-2 text-gray-700">{{ $pelanggan->alamat ?? '—' }}</td>
            </tr>
            <tr class="border-b border-gray-100">
                <td class="py-2 text-gray-500">Username PPPoE</td>
                <td class="py-2 font-mono text-xs text-gray-800">{{ $pelanggan->username }}</td>
            </tr>
            <tr class="border-b border-gray-100">
                <td class="py-2 text-gray-500">Paket</td>
                <td class="py-2 text-gray-700">{{ $pelanggan->paket->nama_paket }}</td>
            </tr>
            <tr class="border-b border-gray-100">
                <td class="py-2 text-gray-500">IP Pool</td>
                <td class="py-2 font-mono text-xs text-gray-700">{{ $pelanggan->ipPool->nama_pool ?? '—' }}</td>
            </tr>
            <tr class="border-b border-gray-100">
                <td class="py-2 text-gray-500">ODP</td>
                <td class="py-2 text-gray-700">{{ $pelanggan->odp->kode_odp ?? '—' }} — {{ $pelanggan->odp->nama_odp ?? '' }}</td>
            </tr>
            <tr class="border-b border-gray-100">
                <td class="py-2 text-gray-500">ODC</td>
                <td class="py-2 text-gray-700">{{ $pelanggan->odp->odc->kode_odc ?? '—' }}</td>
            </tr>
            <tr class="border-b border-gray-100">
                <td class="py-2 text-gray-500">Tanggal Aktif</td>
                <td class="py-2 text-gray-700">{{ \Carbon\Carbon::parse($pelanggan->tanggal_aktif)->format('d/m/Y') }}</td>
            </tr>
            <tr class="border-b border-gray-100">
                <td class="py-2 text-gray-500">Jatuh Tempo</td>
                <td class="py-2 text-gray-700">{{ \Carbon\Carbon::parse($pelanggan->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="py-2 text-gray-500">Status</td>
                <td class="py-2">
                    @if($pelanggan->status === 'aktif')
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Aktif</span>
                    @elseif($pelanggan->status === 'isolir')
                        <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">Isolir</span>
                    @else
                        <span class="bg-gray-100 text-gray-500 text-xs px-2 py-1 rounded-full">Nonaktif</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    @if($pelanggan->latitude && $pelanggan->longitude)
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3">Lokasi Rumah</p>
        <div id="detail-map" style="height: 200px; border-radius: 8px;"></div>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3">Riwayat Invoice</p>
        @forelse($pelanggan->invoice as $inv)
        <div class="flex items-center justify-between py-2 border-b border-gray-100 text-sm">
            <span class="font-mono text-xs text-gray-600">{{ $inv->no_invoice }}</span>
            <span class="text-gray-700">Rp {{ number_format($inv->jumlah, 0, ',', '.') }}</span>
            <span class="text-gray-500">{{ \Carbon\Carbon::parse($inv->tanggal_invoice)->format('d/m/Y') }}</span>
            @if($inv->status === 'paid')
                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Paid</span>
            @elseif($inv->status === 'overdue')
                <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">Overdue</span>
            @else
                <span class="bg-yellow-100 text-yellow-600 text-xs px-2 py-1 rounded-full">Unpaid</span>
            @endif
        </div>
        @empty
        <p class="text-sm text-gray-400">Belum ada invoice.</p>
        @endforelse
    </div>
</div>

@push('scripts')
@if($pelanggan->latitude && $pelanggan->longitude)
<script>
    const map = L.map('detail-map').setView([{{ $pelanggan->latitude }}, {{ $pelanggan->longitude }}], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([{{ $pelanggan->latitude }}, {{ $pelanggan->longitude }}])
        .addTo(map)
        .bindPopup('{{ $pelanggan->nama }}')
        .openPopup();
</script>
@endif
@endpush
@endsection