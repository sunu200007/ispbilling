<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Billing ISP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow p-8 w-full max-w-sm">
        <h2 class="text-xl font-bold text-gray-800 mb-1">Billing ISP</h2>
        <p class="text-sm text-gray-500 mb-6">Silakan login untuk melanjutkan</p>

        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="admin@example.com" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm text-gray-600 mb-1">Password</label>
                <input type="password" name="password"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="••••••••" required>
            </div>
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg transition">
                Login
            </button>
        </form>
    </div>
</body>
</html>