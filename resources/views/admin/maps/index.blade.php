@extends('layouts.app')
@section('title', 'Maps')
@section('content')

<div class="flex items-center justify-between mb-4">
    <h2 class="text-xl font-bold text-gray-800">Maps Jaringan</h2>
    <div class="flex gap-2 text-xs">
        <span class="flex items-center gap-1">
            <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span> Online
        </span>
        <span class="flex items-center gap-1">
            <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span> Offline
        </span>
        <span class="flex items-center gap-1">
            <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span> ODP
        </span>
        <span class="flex items-center gap-1">
            <span class="w-3 h-3 rounded-full bg-yellow-500 inline-block"></span> ODC
        </span>
    </div>
</div>

{{-- Filter --}}
<div class="flex gap-2 mb-4">
    <button onclick="filterMarkers('all')"
        class="px-3 py-1 text-xs rounded-lg border border-gray-300 hover:bg-gray-100 active" id="btn-all">
        Semua
    </button>
    <button onclick="filterMarkers('pelanggan')"
        class="px-3 py-1 text-xs rounded-lg border border-gray-300 hover:bg-gray-100" id="btn-pelanggan">
        Pelanggan
    </button>
    <button onclick="filterMarkers('odp')"
        class="px-3 py-1 text-xs rounded-lg border border-gray-300 hover:bg-gray-100" id="btn-odp">
        ODP
    </button>
    <button onclick="filterMarkers('odc')"
        class="px-3 py-1 text-xs rounded-lg border border-gray-300 hover:bg-gray-100" id="btn-odc">
        ODC
    </button>
    <button onclick="refreshMap()"
        class="px-3 py-1 text-xs rounded-lg border border-blue-300 text-blue-600 hover:bg-blue-50 ml-auto">
        Refresh
    </button>
</div>

{{-- Stats --}}
<div class="grid grid-cols-4 gap-3 mb-4" id="stats">
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Total Pelanggan</p>
        <p class="text-2xl font-bold text-gray-800" id="stat-total">-</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Online</p>
        <p class="text-2xl font-bold text-green-600" id="stat-online">-</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Offline</p>
        <p class="text-2xl font-bold text-red-500" id="stat-offline">-</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Total ODP</p>
        <p class="text-2xl font-bold text-blue-600" id="stat-odp">-</p>
    </div>
</div>

{{-- Map --}}
<div id="map" style="height: 600px; border-radius: 12px; border: 1px solid #e5e7eb; z-index: 0;"></div>

@push('scripts')
<script>
    const map = L.map('map').setView([-7.797068, 110.370529], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    let allMarkers = { pelanggan: [], odp: [], odc: [] };
    let currentFilter = 'all';

    function makeIcon(color) {
        return L.divIcon({
            className: '',
            html: `<div style="
                width: 14px; height: 14px;
                background: ${color};
                border: 2px solid white;
                border-radius: 50%;
                box-shadow: 0 1px 4px rgba(0,0,0,0.4);
            "></div>`,
            iconSize: [14, 14],
            iconAnchor: [7, 7],
        });
    }

    function makeOdpIcon() {
        return L.divIcon({
            className: '',
            html: `<div style="
                width: 16px; height: 16px;
                background: #3B82F6;
                border: 2px solid white;
                border-radius: 3px;
                box-shadow: 0 1px 4px rgba(0,0,0,0.4);
            "></div>`,
            iconSize: [16, 16],
            iconAnchor: [8, 8],
        });
    }

    function makeOdcIcon() {
        return L.divIcon({
            className: '',
            html: `<div style="
                width: 20px; height: 20px;
                background: #F59E0B;
                border: 2px solid white;
                border-radius: 3px;
                box-shadow: 0 1px 4px rgba(0,0,0,0.4);
            "></div>`,
            iconSize: [20, 20],
            iconAnchor: [10, 10],
        });
    }

    function loadData() {
        fetch('{{ route("maps.data") }}')
            .then(r => r.json())
            .then(data => {
                // Clear existing markers
                allMarkers.pelanggan.forEach(m => map.removeLayer(m));
                allMarkers.odp.forEach(m => map.removeLayer(m));
                allMarkers.odc.forEach(m => map.removeLayer(m));
                allMarkers = { pelanggan: [], odp: [], odc: [] };

                const online  = data.pelanggan.filter(p => p.online).length;
                const offline = data.pelanggan.filter(p => !p.online).length;

                document.getElementById('stat-total').textContent   = data.pelanggan.length;
                document.getElementById('stat-online').textContent  = online;
                document.getElementById('stat-offline').textContent = offline;
                document.getElementById('stat-odp').textContent     = data.odp.length;

                // Pelanggan markers
                data.pelanggan.forEach(p => {
                    const color  = p.online ? '#22C55E' : '#EF4444';
                    const status = p.online ? 'Online' : 'Offline';
                    const marker = L.marker([p.lat, p.lng], { icon: makeIcon(color) })
                        .bindPopup(`
                            <div style="min-width:180px;font-size:13px">
                                <div style="font-weight:600;margin-bottom:4px">${p.nama}</div>
                                <div style="color:#6B7280;margin-bottom:2px">@${p.username}</div>
                                <div>IP: <b>${p.ip ?? '-'}</b></div>
                                <div>Paket: ${p.paket}</div>
                                <div>ODP: ${p.odp}</div>
                                <div style="margin-top:6px">
                                    <span style="
                                        background:${p.online ? '#DCFCE7' : '#FEE2E2'};
                                        color:${p.online ? '#15803D' : '#DC2626'};
                                        padding:2px 8px;border-radius:999px;font-size:11px
                                    ">${status}</span>
                                </div>
                            </div>
                        `);
                    allMarkers.pelanggan.push(marker);
                });

                // ODP markers
                data.odp.forEach(o => {
                    const marker = L.marker([o.lat, o.lng], { icon: makeOdpIcon() })
                        .bindPopup(`
                            <div style="min-width:160px;font-size:13px">
                                <div style="font-weight:600;margin-bottom:4px">${o.kode}</div>
                                <div>${o.nama}</div>
                                <div style="color:#6B7280">ODC: ${o.odc}</div>
                                <div>Port: ${o.port}</div>
                            </div>
                        `);
                    allMarkers.odp.push(marker);
                });

                // ODC markers
                data.odc.forEach(o => {
                    const marker = L.marker([o.lat, o.lng], { icon: makeOdcIcon() })
                        .bindPopup(`
                            <div style="min-width:160px;font-size:13px">
                                <div style="font-weight:600;margin-bottom:4px">${o.kode}</div>
                                <div>${o.nama}</div>
                                <div>Port: ${o.port}</div>
                            </div>
                        `);
                    allMarkers.odc.push(marker);
                });

                filterMarkers(currentFilter);
            });
    }

    function filterMarkers(type) {
        currentFilter = type;

        // Update button styles
        ['all', 'pelanggan', 'odp', 'odc'].forEach(t => {
            const btn = document.getElementById('btn-' + t);
            if (btn) btn.style.background = '';
        });
        const activeBtn = document.getElementById('btn-' + type);
        if (activeBtn) activeBtn.style.background = '#DBEAFE';

        // Show/hide markers
        const show = {
            pelanggan: type === 'all' || type === 'pelanggan',
            odp:       type === 'all' || type === 'odp',
            odc:       type === 'all' || type === 'odc',
        };

        Object.keys(allMarkers).forEach(key => {
            allMarkers[key].forEach(m => {
                if (show[key]) {
                    m.addTo(map);
                } else {
                    map.removeLayer(m);
                }
            });
        });
    }

    function refreshMap() {
        loadData();
    }

    // Auto refresh setiap 60 detik
    loadData();
    setInterval(loadData, 60000);
</script>
@endpush
@endsection
