<x-app-layout title="Dashboard">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-8">Halo, {{ auth()->user()->name }}! 👋</h1>

        {{-- Stats --}}
        <div class="grid sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white/5 border border-white/10 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center"><i data-lucide="ticket" class="w-6 h-6 text-purple-400"></i></div>
                <div><p class="text-2xl font-bold">{{ $activeTickets }}</p><p class="text-gray-400 text-sm">Tiket Aktif</p></div>
            </div>
            <div class="bg-white/5 border border-white/10 rounded-2xl p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/20 flex items-center justify-center"><i data-lucide="receipt" class="w-6 h-6 text-emerald-400"></i></div>
                <div><p class="text-2xl font-bold">{{ $totalOrders }}</p><p class="text-gray-400 text-sm">Total Order</p></div>
            </div>
            <a href="{{ route('events.index') }}" class="bg-white/5 border border-white/10 rounded-2xl p-5 flex items-center gap-4 hover:border-purple-500/50 transition">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center"><i data-lucide="search" class="w-6 h-6 text-indigo-400"></i></div>
                <div><p class="font-semibold">Jelajahi Event</p><p class="text-gray-400 text-sm">Temukan event →</p></div>
            </a>
        </div>

        {{-- Quick links --}}
        <div class="flex flex-wrap gap-3 mb-8">
            <a href="{{ route('tickets.index') }}" class="bg-purple-600 hover:bg-purple-700 px-5 py-2 rounded-xl text-sm font-medium transition">🎫 Tiket Saya</a>
            <a href="{{ route('orders.index') }}" class="bg-white/5 border border-white/10 hover:bg-white/10 px-5 py-2 rounded-xl text-sm font-medium transition">📋 Riwayat Order</a>
            <a href="{{ route('profile.edit') }}" class="bg-white/5 border border-white/10 hover:bg-white/10 px-5 py-2 rounded-xl text-sm font-medium transition">👤 Profil</a>
        </div>

        {{-- Recent Orders --}}
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
            <h2 class="text-lg font-semibold mb-4">Order Terbaru</h2>
            @forelse($recentOrders as $order)
                <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between py-3 border-b border-white/5 last:border-0 hover:bg-white/5 -mx-2 px-2 rounded-lg transition">
                    <div>
                        <p class="font-medium">{{ $order->event->title }}</p>
                        <p class="text-xs text-gray-500">{{ $order->order_code }} · {{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ match($order->status) { 'paid' => 'bg-emerald-500/20 text-emerald-400', 'pending' => 'bg-yellow-500/20 text-yellow-400', 'expired' => 'bg-gray-500/20 text-gray-400', 'refunded' => 'bg-blue-500/20 text-blue-400', default => 'bg-red-500/20 text-red-400' } }}">{{ $order->status }}</span>
                    </div>
                </a>
            @empty
                <p class="text-gray-500 text-sm">Belum ada order.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
