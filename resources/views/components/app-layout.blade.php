<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Eventify' }} — Eventify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        .gradient-text { background: linear-gradient(135deg, #a78bfa, #c084fc, #e879f9); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="sticky top-0 z-50 border-b border-white/10 bg-slate-950/80 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl font-bold">
                <span class="w-9 h-9 rounded-xl bg-purple-600 flex items-center justify-center"><i data-lucide="ticket" class="w-5 h-5"></i></span>
                Eventify
            </a>

            <div class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('home') }}" class="hover:text-purple-400 transition {{ request()->routeIs('home') ? 'text-purple-400 font-semibold' : 'text-gray-300' }}">Beranda</a>
                <a href="{{ route('events.index') }}" class="hover:text-purple-400 transition {{ request()->routeIs('events.*') ? 'text-purple-400 font-semibold' : 'text-gray-300' }}">Jelajahi Event</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:text-purple-400 transition {{ request()->routeIs('dashboard') ? 'text-purple-400 font-semibold' : 'text-gray-300' }}">Dashboard</a>
                    @if(auth()->user()->hasAdminAccess())
                        <a href="{{ route('organizer.dashboard') }}" class="hover:text-purple-400 transition text-gray-300">Organizer</a>
                    @endif
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-purple-400 transition text-gray-300">Admin</a>
                    @endif
                @endauth
            </div>

            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg font-medium transition">Daftar</a>
                @else
                    <span class="text-sm text-gray-400 hidden md:block">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-sm text-gray-400 hover:text-white flex items-center gap-1"><i data-lucide="log-out" class="w-4 h-4"></i></button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4"><div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div></div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4"><div class="bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div></div>
    @endif

    {{-- Content --}}
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="border-t border-white/10 mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-8 text-center text-gray-500 text-sm">
            © {{ date('Y') }} Eventify. All rights reserved.
        </div>
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>
