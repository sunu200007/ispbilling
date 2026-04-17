@extends('layouts.app')
@section('title', 'Detail Invoice')
@section('content')

<div class="max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Detail Invoice</h2>
        <div class="flex gap-2">
            <a href="{{ route('invoice.index') }}"
                class="text-gray-500 text-sm px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-8">
        {{-- Header --}}
        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">INVOICE</h1>
                <p class="font-mono text-sm text-gray-500 mt-1">{{ $invoice->no_invoice }}</p>
            </div>
            <div class="text-right">
                @if($invoice->status === 'paid')
                    <span class="bg-green-100 text-green-700 text-sm px-3 py-1 rounded-full font-medium">PAID</span>
                @elseif($invoice->status === 'overdue')
                    <span class="bg-red-100 text-red-600 text-sm px-3 py-1 rounded-full font-medium">OVERDUE</span>
                @else
                    <span class="bg-yellow-100 text-yellow-600 text-sm px-3 py-1 rounded-full font-medium">UNPAID</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Tagihan Kepada</p>
                <p class="font-semibold text-gray-800">{{ $invoice->pelanggan->nama }}</p>
                <p class="text-sm text-gray-600">{{ $invoice->pelanggan->username }}</p>
                <p class="text-sm text-gray-600">{{ $invoice->pelanggan->no_hp ?? '-' }}</p>
                <p class="text-sm text-gray-600">{{ $invoice->pelanggan->alamat ?? '-' }}</p>
            </div>

            <div class="text-right">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Info Invoice</p>

                {{-- Tanggal Invoice --}}
                <p class="text-sm text-gray-600">
                    Tgl Invoice:
                    <span class="text-gray-800">
                        {{ \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d/m/Y') }}
                    </span>
                </p>

                {{-- ✅ EXPIRATION (EDITABLE) --}}
                <div class="mt-2">
                    <p class="text-sm text-gray-600">Expiration:</p>

                    <div class="flex items-center justify-end gap-3">
                        <span class="text-gray-800 text-sm">
                            {{ \Carbon\Carbon::parse($invoice->pelanggan->tanggal_jatuh_tempo)->format('d M Y') }}
                        </span>

                        <button onclick="toggleEditExpiration()"
                            class="text-xs text-blue-600 hover:underline">
                            Edit
                        </button>
                    </div>

                    <div id="form-expiration" style="display:none" class="mt-2">
                        <form method="POST" action="{{ route('pelanggan.expiration', $invoice->pelanggan) }}" class="flex gap-2 justify-end">
                            @csrf
                            <input type="date" name="tanggal_jatuh_tempo"
                                value="{{ $invoice->pelanggan->tanggal_jatuh_tempo }}"
                                class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">

                            <button type="submit"
                                class="bg-blue-600 text-white text-xs px-3 py-1 rounded-lg hover:bg-blue-700">
                                Simpan
                            </button>

                            <button type="button" onclick="toggleEditExpiration()"
                                class="text-gray-500 text-xs px-3 py-1 rounded-lg border border-gray-300 hover:bg-gray-50">
                                Batal
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Dibayar --}}
                @if($invoice->dibayar_at)
                <p class="text-sm text-gray-600 mt-2">
                    Dibayar:
                    <span class="text-gray-800">
                        {{ \Carbon\Carbon::parse($invoice->dibayar_at)->format('d/m/Y H:i') }}
                    </span>
                </p>
                @endif
            </div>
        </div>

        {{-- TABLE --}}
        <table class="w-full text-sm mb-8">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 text-left font-medium text-gray-600">Deskripsi</th>
                    <th class="px-4 py-3 text-right font-medium text-gray-600">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-gray-100">
                    <td class="px-4 py-3 text-gray-800">
                        Layanan Internet — {{ $invoice->pelanggan->paket->nama_paket }}
                        <span class="text-gray-400 text-xs block">
                            {{ \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('F Y') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">
                        Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="px-4 py-3 text-right font-semibold text-gray-700">Total</td>
                    <td class="px-4 py-3 text-right font-bold text-gray-800 text-base">
                        Rp {{ number_format($invoice->jumlah, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        {{-- METODE BAYAR --}}
        @if($invoice->metode_bayar)
        <p class="text-sm text-gray-500">
            Metode Bayar:
            <span class="text-gray-800 font-medium">
                {{ strtoupper($invoice->metode_bayar) }}
            </span>
        </p>
        @endif

        {{-- BUTTON BAYAR --}}
        @if($invoice->status !== 'paid')
        <div class="mt-6 pt-6 border-t border-gray-100">
            <button onclick="openBayar()"
                class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                Tandai Sudah Dibayar
            </button>
        </div>
        @endif
    </div>
</div>

{{-- MODAL BAYAR --}}
<div id="modal-bayar" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:999;align-items:center;justify-content:center;">
    <div class="bg-white rounded-xl p-6 w-80">
        <h3 class="font-bold text-gray-800 mb-4">Konfirmasi Pembayaran</h3>

        <form method="POST" action="{{ route('invoice.bayar', $invoice) }}">
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
function openBayar() {
    document.getElementById('modal-bayar').style.display = 'flex';
}

function closeBayar() {
    document.getElementById('modal-bayar').style.display = 'none';
}

function toggleEditExpiration() {
    const form = document.getElementById('form-expiration');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
@endpush

@endsection