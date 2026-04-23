<x-admin-layout header="Events">
    <form method="GET" class="flex gap-3 mb-6">
        <select name="status" class="border border-gray-200 rounded-xl px-4 py-2 text-sm" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach(['draft','published','ended','cancelled'] as $s)<option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>@endforeach
        </select>
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead><tr class="bg-gray-50 text-gray-500 border-b"><th class="px-5 py-3">Event</th><th class="px-5 py-3">Organizer</th><th class="px-5 py-3">Tanggal</th><th class="px-5 py-3">Status</th><th class="px-5 py-3">Aksi</th></tr></thead>
            <tbody>
            @forelse($events as $event)
                <tr class="border-b border-gray-50">
                    <td class="px-5 py-3"><p class="font-medium">{{ $event->title }}</p><p class="text-xs text-gray-400">{{ $event->category->name ?? '-' }}</p></td>
                    <td class="px-5 py-3 text-gray-500">{{ $event->organizer->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $event->start_at->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.events.update', $event) }}" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="status" class="border border-gray-200 rounded-lg px-2 py-1 text-xs">
                                @foreach(['draft','published','ended','cancelled'] as $s)<option value="{{ $s }}" {{ $event->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>@endforeach
                            </select>
                            <button class="text-purple-600 text-xs hover:underline">Update</button>
                        </form>
                    </td>
                    <td class="px-5 py-3"><a href="{{ route('events.show', $event->slug) }}" class="text-blue-500 text-xs hover:underline" target="_blank">View</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">Tidak ada event.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
</x-admin-layout>
