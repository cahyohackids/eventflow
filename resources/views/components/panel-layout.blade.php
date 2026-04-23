<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel' }} — Eventify</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>* { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white border-r border-gray-200 min-h-screen fixed left-0 top-0 flex flex-col z-40">
        <div class="p-5 border-b border-gray-100">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold text-gray-800">
                <span class="w-8 h-8 rounded-lg bg-purple-600 flex items-center justify-center"><i data-lucide="ticket" class="w-4 h-4 text-white"></i></span>
                Eventify
            </a>
            <p class="text-xs text-purple-600 font-medium mt-1 uppercase tracking-wide">{{ $panelName ?? 'Panel' }}</p>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            {{ $sidebar }}
        </nav>

        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <div class="ml-64 flex-1 min-h-screen">
        {{-- Topbar --}}
        <header class="h-14 border-b border-gray-200 bg-white flex items-center justify-between px-6 sticky top-0 z-30">
            <h1 class="text-lg font-semibold text-gray-800">{{ $header ?? '' }}</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm text-gray-500 hover:text-gray-800 flex items-center gap-1"><i data-lucide="log-out" class="w-4 h-4"></i> Keluar</button>
            </form>
        </header>

        {{-- Flash --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm mb-4">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-4">{{ session('error') }}</div>
            @endif
        </div>

        {{-- Content --}}
        <main class="p-6">
            {{ $slot }}
        </main>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
