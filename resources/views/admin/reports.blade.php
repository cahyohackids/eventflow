<x-admin-layout header="Reports">
    {{-- Monthly Revenue --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Revenue per Bulan</h2>
        @if($monthlyRevenue->isEmpty())
            <p class="text-gray-400 text-sm">Belum ada data revenue.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead><tr class="text-gray-500 border-b"><th class="pb-3">Bulan</th><th class="pb-3">Revenue</th><th class="pb-3">Jumlah Order</th></tr></thead>
                    <tbody>
                    @foreach($monthlyRevenue as $m)
                        <tr class="border-b border-gray-50">
                            <td class="py-3 font-medium">{{ $m->month }}</td>
                            <td class="py-3 font-semibold text-emerald-600">Rp {{ number_format($m->revenue, 0, ',', '.') }}</td>
                            <td class="py-3">{{ $m->order_count }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Top Events --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Top Events by Revenue</h2>
        <table class="w-full text-sm text-left">
            <thead><tr class="text-gray-500 border-b"><th class="pb-3">#</th><th class="pb-3">Event</th><th class="pb-3">Revenue</th><th class="pb-3">Orders</th></tr></thead>
            <tbody>
            @foreach($topEvents as $i => $event)
                <tr class="border-b border-gray-50">
                    <td class="py-3 text-gray-400">{{ $i + 1 }}</td>
                    <td class="py-3 font-medium">{{ $event->title }}</td>
                    <td class="py-3 font-semibold text-emerald-600">Rp {{ number_format($event->revenue ?? 0, 0, ',', '.') }}</td>
                    <td class="py-3">{{ $event->paid_orders ?? 0 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Status Breakdown --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold mb-4">Order Status Breakdown</h2>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
            @foreach(['pending','paid','expired','refunded','failed'] as $s)
                <div class="text-center p-4 rounded-xl {{ match($s) { 'paid' => 'bg-emerald-50', 'pending' => 'bg-yellow-50', 'expired' => 'bg-gray-50', 'refunded' => 'bg-blue-50', default => 'bg-red-50' } }}">
                    <p class="text-2xl font-bold {{ match($s) { 'paid' => 'text-emerald-600', 'pending' => 'text-yellow-600', 'expired' => 'text-gray-600', 'refunded' => 'text-blue-600', default => 'text-red-600' } }}">{{ $statusBreakdown[$s] ?? 0 }}</p>
                    <p class="text-xs text-gray-500 mt-1 capitalize">{{ $s }}</p>
                </div>
            @endforeach
        </div>
    </div>
</x-admin-layout>
