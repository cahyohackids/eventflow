<x-organizer-layout header="Kelola Event">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold">Event Saya</h2>
        <a href="{{ route('organizer.events.create') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition">+ Buat Event</a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead><tr class="text-gray-500 border-b bg-gray-50">
                <th class="px-5 py-3 font-medium">Event</th><th class="px-5 py-3 font-medium">Tanggal</th><th class="px-5 py-3 font-medium">Terjual</th><th class="px-5 py-3 font-medium">Status</th><th class="px-5 py-3 font-medium">Aksi</th>
            </tr></thead>
            <tbody>
            @forelse($events as $event)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-5 py-4"><p class="font-semibold">{{ $event->title }}</p><p class="text-xs text-gray-400">{{ $event->category->name ?? '-' }}</p></td>
                    <td class="px-5 py-4 text-gray-500">{{ $event->start_at->format('d M Y') }}</td>
                    <td class="px-5 py-4">{{ $event->getTotalSold() }}/{{ $event->getTotalQuota() }}</td>
                    <td class="px-5 py-4"><span class="text-xs px-2 py-0.5 rounded-full {{ match($event->status) { 'published' => 'bg-emerald-100 text-emerald-700', 'draft' => 'bg-yellow-100 text-yellow-700', 'ended' => 'bg-gray-100 text-gray-600', 'cancelled' => 'bg-red-100 text-red-700' } }}">{{ $event->status }}</span></td>
                    <td class="px-5 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('organizer.events.edit', $event) }}" class="text-purple-600 hover:underline text-xs">Edit</a>
                            <a href="{{ route('organizer.events.orders', $event) }}" class="text-blue-600 hover:underline text-xs">Orders</a>
                            <a href="{{ route('organizer.events.export.csv', $event) }}" class="text-gray-500 hover:underline text-xs">CSV</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada event.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
</x-organizer-layout>
