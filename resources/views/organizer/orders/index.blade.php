<x-organizer-layout header="Orders: {{ $event->title }}">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold">{{ $orders->total() }} Order</h2>
        <a href="{{ route('organizer.events.export.csv', $event) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-xl text-sm transition">📥 Export CSV</a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead><tr class="text-gray-500 border-b bg-gray-50">
                <th class="px-5 py-3 font-medium">Order</th><th class="px-5 py-3 font-medium">Customer</th><th class="px-5 py-3 font-medium">Total</th><th class="px-5 py-3 font-medium">Status</th><th class="px-5 py-3 font-medium">Tanggal</th>
            </tr></thead>
            <tbody>
            @forelse($orders as $order)
                <tr class="border-b border-gray-50">
                    <td class="px-5 py-3 font-mono text-xs">{{ $order->order_code }}</td>
                    <td class="px-5 py-3">{{ $order->user->name }}<br><span class="text-xs text-gray-400">{{ $order->user->email }}</span></td>
                    <td class="px-5 py-3 font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="px-5 py-3"><span class="text-xs px-2 py-0.5 rounded-full {{ match($order->status) { 'paid' => 'bg-emerald-100 text-emerald-700', 'pending' => 'bg-yellow-100 text-yellow-700', default => 'bg-gray-100 text-gray-600' } }}">{{ $order->status }}</span></td>
                    <td class="px-5 py-3 text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada order.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</x-organizer-layout>
