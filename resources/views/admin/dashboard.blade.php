<x-admin-layout header="Dashboard">
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Orders (Paid)</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Total Events</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalEvents }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase mb-1">Total Users</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold mb-4">Order Terbaru</h2>
        <table class="w-full text-sm text-left">
            <thead><tr class="text-gray-500 border-b"><th class="pb-3">Order</th><th class="pb-3">Event</th><th class="pb-3">User</th><th class="pb-3">Total</th><th class="pb-3">Status</th></tr></thead>
            <tbody>
            @forelse($recentOrders as $order)
                <tr class="border-b border-gray-50">
                    <td class="py-3 font-mono text-xs">{{ $order->order_code }}</td>
                    <td class="py-3">{{ Str::limit($order->event->title, 25) }}</td>
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
</x-admin-layout>
