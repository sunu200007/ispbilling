@extends('layouts.app')
@section('title', 'Invoice')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">Manajemen Invoice</h2>
    <div class="flex gap-2">
        <form method="POST" action="{{ route('invoice.update-overdue') }}">
            @csrf
            <button type="submit"
                class="border border-yellow-400 text-yellow-600 hover:bg-yellow-50 text-sm font-medium px-4 py-2 rounded-lg transition">
                Update Overdue
            </button>
        </form>
        <form method="POST" action="{{ route('invoice.generate-bulk') }}">
            @csrf
            <button type="submit"
                class="border border-green-500 text-green-600 hover:bg-green-50 text-sm font-medium px-4 py-2 rounded-lg transition"
                onclick="return confirm('Generate invoice bulan ini untuk semua pelanggan aktif?')">
                Generate Bulan Ini
            </button>
        </form>
        <a href="{{ route('invoice.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            + Buat Invoice
        </a>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-50 text-green-700 text-sm rounded-lg px-4 py-3 mb-4">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-50 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">{{ session('error') }}</div>
@endif

{{-- Filter --}}
<form method="GET" class="flex gap-3 mb-4">
    <input type="text" name="search" value="{{ request('search') }}"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
        placeholder="Cari nama / username / no invoice...">
    <select name="status"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Status</option>
        <option value="unpaid"  {{ request('status') === 'unpaid'  ? 'selected' : '' }}>Unpaid</option>
        <option value="paid"    {{ request('status') === 'paid'    ? 'selected' : '' }}>Paid</option>
        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
    </select>
    <button type="submit"
        class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-lg transition">
        Filter
    </button>
    <a href="{{ route('invoice.index') }}"
        class="text-gray-400 hover:text-gray-600 text-sm px-3 py-2 rounded-lg transition">
        Reset
    </a>
</form>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-5 py-3 font-medium">No Invoice</th>
                <th class="px-5 py-3 font-medium">Pelanggan</th>
                <th class="px-5 py-3 font-medium">Jumlah</th>
                <th class="px-5 py-3 font-medium">Tgl Invoice</th>
                <th class="px-5 py-3 font-medium">Jatuh Tempo</th>
                <th class="px-5 py-3 font-medium">Status</th>
                <th class="px-5 py-3 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($invoice as $inv)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-mono text-xs text-gray-800">{{ $inv->no_invoice }}</td>
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $inv->pelanggan->nama }}</p>
                    <p class="text-xs text-gray-400">{{ $inv->pelanggan->username }}</p>
                </td>
                <td class="px-5 py-3 text-gray-700 font-medium">
                    Rp {{ number_format($inv->jumlah, 0, ',', '.') }}
                </td>
                <td class="px-5 py-3 text-gray-600">
                    {{ \Carbon\Carbon::parse($inv->tanggal_invoice)->format('d/m/Y') }}
                </td>
                <td class="px-5 py-3 text-gray-600">
                    {{ \Carbon\Carbon::parse($inv->tanggal_jatuh_tempo)->format('d/m/Y') }}
                </td>
                <td class="px-5 py-3">
                    @if($inv->status === 'paid')
                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Paid</span>
                    @elseif($inv->status === 'overdue')
                        <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">Overdue</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-600 text-xs px-2 py-1 rounded-full">Unpaid</span>
                    @endif
                </td>
                <td class="px-5 py-3 flex gap-2 items-center">
                    <a href="{{ route('invoice.show', $inv) }}"
                        class="text-blue-600 hover:underline text-xs">Detail</a>
                    @if($inv->status !== 'paid')
                        <button onclick="openBayar({{ $inv->id }})"
                            class="text-green-600 hover:underline text-xs">Bayar</button>
                        <form method="POST" action="{{ route('invoice.destroy', $inv) }}"
                            onsubmit="return confirm('Hapus invoice ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-xs">Hapus</button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-5 py-8 text-center text-gray-400">Belum ada invoice.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $invoice->withQueryString()->links() }}
</div>

{{-- Modal Bayar --}}
<div id="modal-bayar" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:999;align-items:center;justify-content:center;">
    <div class="bg-white rounded-xl p-6 w-80 shadow-xl">
        <h3 class="font-bold text-gray-800 mb-4">Konfirmasi Pembayaran</h3>
        <form id="form-bayar" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Metode Bayar</label>
                <select name="metode_bayar"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer Bank</option>
                    <option value="qris">QRIS</option>
                    <option value="va">Virtual Account</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 rounded-lg transition">
                    Konfirmasi
                </button>
                <button type="button" onclick="closeBayar()"
                    class="flex-1 border border-gray-300 text-gray-600 text-sm py-2 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openBayar(id) {
        document.getElementById('form-bayar').action = '/invoice/' + id + '/bayar';
        document.getElementById('modal-bayar').style.display = 'flex';
    }
    function closeBayar() {
        document.getElementById('modal-bayar').style.display = 'none';
    }
</script>
@endpush
@endsection
