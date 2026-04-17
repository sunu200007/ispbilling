@extends('layouts.app')
@section('title', 'Buat Invoice')
@section('content')

<div class="max-w-xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Buat Invoice</h2>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('invoice.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Pelanggan</label>
                <select name="pelanggan_id" id="pelanggan_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($pelanggan as $p)
                        <option value="{{ $p->id }}"
                            data-harga="{{ $p->paket->harga }}"
                            {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} ({{ $p->username }}) — {{ $p->paket->nama_paket }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Jumlah (Rp)</label>
                <input type="number" name="jumlah" id="jumlah" value="{{ old('jumlah') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Otomatis dari paket" required>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Tanggal Invoice</label>
                    <input type="date" name="tanggal_invoice" value="{{ old('tanggal_invoice', date('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Jatuh Tempo</label>
                    <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Simpan
                </button>
                <a href="{{ route('invoice.index') }}"
                    class="text-gray-500 hover:text-gray-700 text-sm px-5 py-2 rounded-lg border border-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('pelanggan_id').addEventListener('change', function() {
        const harga = this.options[this.selectedIndex].dataset.harga;
        if (harga) document.getElementById('jumlah').value = harga;
    });
</script>
@endpush
@endsection
