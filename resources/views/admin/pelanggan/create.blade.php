@extends('layouts.app')
@section('title', 'Tambah Pelanggan')
@section('content')

<div class="max-w-2xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah Pelanggan</h2>
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('pelanggan.store') }}">
            @csrf

            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3">Data Pelanggan</p>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Budi Santoso" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="08123456789">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Alamat</label>
                <textarea name="alamat" rows="2"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Jl. Mawar No.1">{{ old('alamat') }}</textarea>
            </div>

            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3 mt-5">Akun PPPoE</p>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="budi.santoso" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Password</label>
                    <input type="text" name="password"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="min. 6 karakter" required>
                </div>
            </div>

            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3 mt-5">Paket & Pool</p>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Paket</label>
                    <select name="paket_id" id="paket_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Paket --</option>
                        @foreach($paket as $p)
                            <option value="{{ $p->id }}" {{ old('paket_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_paket }} — Rp {{ number_format($p->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">IP Pool</label>
                    <select name="ip_pool_id" id="ip_pool_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Pilih Paket Dulu --</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">ODP</label>
                <select name="odp_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Pilih ODP --</option>
                    @foreach($odp as $o)
                        <option value="{{ $o->id }}" {{ old('odp_id') == $o->id ? 'selected' : '' }}>
                            {{ $o->kode_odp }} — {{ $o->nama_odp }} ({{ $o->odc->kode_odc }})
                        </option>
                    @endforeach
                </select>
            </div>

            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3 mt-5">Lokasi Rumah</p>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Latitude</label>
                    <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="-7.123456">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Longitude</label>
                    <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="110.123456">
                </div>
            </div>

            {{-- Mini map untuk klik koordinat --}}
            <div class="mb-4">
                <p class="text-xs text-gray-500 mb-2">Klik peta untuk mengisi koordinat otomatis</p>
                <div id="mini-map" style="height: 250px; border-radius: 8px; border: 1px solid #e5e7eb;"></div>
            </div>

            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-3 mt-5">Langganan</p>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Tanggal Aktif</label>
                    <input type="date" name="tanggal_aktif" value="{{ old('tanggal_aktif', date('Y-m-d')) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Tanggal Jatuh Tempo</label>
                    <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1">Status</label>
                <select name="status"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                    <option value="isolir">Isolir</option>
                </select>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Simpan & Sync RADIUS
                </button>
                <a href="{{ route('pelanggan.index') }}"
                    class="text-gray-500 hover:text-gray-700 text-sm px-5 py-2 rounded-lg border border-gray-300 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Mini map
    const map = L.map('mini-map').setView([-7.797068, 110.370529], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    let marker;

    map.on('click', function(e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);
        document.getElementById('latitude').value  = lat;
        document.getElementById('longitude').value = lng;
        if (marker) marker.remove();
        marker = L.marker([lat, lng]).addTo(map);
    });

    // Load pools saat paket dipilih
    document.getElementById('paket_id').addEventListener('change', function() {
        const paketId = this.value;
        const poolSelect = document.getElementById('ip_pool_id');
        poolSelect.innerHTML = '<option value="">Memuat...</option>';

        if (!paketId) {
            poolSelect.innerHTML = '<option value="">-- Pilih Paket Dulu --</option>';
            return;
        }

        fetch(`/get-pools?paket_id=${paketId}`)
            .then(r => r.json())
            .then(pools => {
                poolSelect.innerHTML = '<option value="">-- Pilih Pool --</option>';
                if (pools.length === 0) {
                    poolSelect.innerHTML = '<option value="">Tidak ada pool tersedia</option>';
                    return;
                }
                pools.forEach(pool => {
                    const opt = document.createElement('option');
                    opt.value = pool.id;
                    opt.disabled = pool.penuh;
                    opt.textContent = `${pool.nama_pool} (sisa: ${pool.sisa}/${pool.kapasitas})${pool.penuh ? ' — PENUH' : ''}`;
                    poolSelect.appendChild(opt);
                });
            });
    });
</script>
@endpush
@endsection