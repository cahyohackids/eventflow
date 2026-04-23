<x-app-layout title="Riwayat Order">
    <div class="max-w-5xl mx-auto px-4 py-10">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold">Riwayat Order</h1>
            <a href="{{ route('orders.export.csv') }}" class="bg-white/5 border border-white/10 hover:bg-white/10 px-4 py-2 rounded-xl text-sm transition">📥 Export CSV</a>
        </div>

        {{-- Filter --}}
        <form method="GET" class="flex gap-3 mb-6">
            <select name="status" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-sm text-white" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['pending','paid','expired','refunded','failed'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </form>

        <div class="space-y-3">
            @forelse($orders as $order)
                <a href="{{ route('orders.show', $order) }}" class="block bg-white/5 border border-white/10 rounded-2xl p-5 hover:border-purple-500/50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold">{{ $order->event->title }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $order->order_code }} · {{ $order->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            <span class="text-xs px-2 py-0.5 rounded-full {{ match($order->status) { 'paid' => 'bg-emerald-500/20 text-emerald-400', 'pending' => 'bg-yellow-500/20 text-yellow-400', 'expired' => 'bg-gray-500/20 text-gray-400', 'refunded' => 'bg-blue-500/20 text-blue-400', default => 'bg-red-500/20 text-red-400' } }}">{{ $order->status }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="text-gray-500 text-center py-10">Belum ada order.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $orders->links() }}</div>
    </div>
</x-app-layout>
