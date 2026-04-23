<x-organizer-layout header="Dashboard">
    {{-- Stats --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Revenue</p>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Orders (Paid)</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Tiket Terjual</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalSold }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Event Aktif</p>
            <p class="text-2xl font-bold text-gray-800">{{ $activeEvents }}</p>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold mb-4">Order Terbaru</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead><tr class="text-gray-500 border-b">
                    <th class="pb-3 font-medium">Order</th><th class="pb-3 font-medium">Event</th><th class="pb-3 font-medium">Customer</th><th class="pb-3 font-medium">Total</th><th class="pb-3 font-medium">Status</th>
                </tr></thead>
                <tbody>
                @forelse($recentOrders as $order)
                    <tr class="border-b border-gray-50">
                        <td class="py-3 font-mono text-xs">{{ $order->order_code }}</td>
                        <td class="py-3">{{ Str::limit($order->event->title, 30) }}</td>
                        <td class="py-3 text-gray-500">{{ $order->user->name }}</td>
                        <td class="py-3 font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="py-3"><span class="text-xs px-2 py-0.5 rounded-full {{ match($order->status) { 'paid' => 'bg-emerald-100 text-emerald-700', 'pending' => 'bg-yellow-100 text-yellow-700', default => 'bg-gray-100 text-gray-600' } }}">{{ $order->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-gray-400">Belum ada order.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-organizer-layout>
