<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Billing ISP')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0">
        <div class="px-6 py-5 border-b border-gray-700">
            <h1 class="text-lg font-bold">Billing ISP</h1>
            <p class="text-xs text-gray-400 mt-1">{{ Auth::user()->name }}</p>
        </div>
        <nav class="flex-1 px-4 py-4 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('dashboard') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                Dashboard
            </a>
            <a href="{{ route('pelanggan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('pelanggan.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                Pelanggan
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-gray-700">
                Invoice
            </a>
            <a href="{{ route('ip-pool.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('ip-pool.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                IP Pool
            </a>
            <a href="{{ route('paket.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('paket.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                Paket
            </a>
            <a href="{{ route('odc.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('odc.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                ODC
            </a>
            <a href="{{ route('odp.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm {{ request()->routeIs('odp.*') ? 'bg-blue-600' : 'hover:bg-gray-700' }}">
                ODP
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-gray-700">
                Maps
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-gray-700">
                RADIUS
            </a>
        </nav>
        <div class="px-4 py-4 border-t border-gray-700">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-gray-700">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 overflow-y-auto">
        <div class="px-8 py-6">
            @yield('content')
        </div>
    </main>

</div>

@stack('scripts')
</body>
</html>