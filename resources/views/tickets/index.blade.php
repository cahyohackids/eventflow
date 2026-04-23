<x-app-layout title="Tiket Saya">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold mb-8">🎫 Tiket Saya</h1>

        @if($attendees->isEmpty())
            <div class="text-center py-16 bg-white/5 border border-white/10 rounded-2xl">
                <i data-lucide="ticket" class="w-16 h-16 text-gray-600 mx-auto mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-400">Belum Ada Tiket</h3>
                <p class="text-gray-500 mt-2 mb-4">Beli tiket event untuk melihatnya di sini.</p>
                <a href="{{ route('events.index') }}" class="bg-purple-600 hover:bg-purple-700 px-6 py-2.5 rounded-xl text-sm font-medium transition">Jelajahi Event</a>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($attendees as $att)
                    <a href="{{ route('tickets.show', $att) }}"
                       class="bg-white/5 border border-white/10 rounded-2xl p-5 hover:border-purple-500/50 transition">
                        <p class="font-mono text-purple-400 text-sm mb-2">{{ $att->ticket_code }}</p>
                        <h3 class="font-semibold">{{ $att->orderItem->order->event->title ?? 'Event' }}</h3>
                        <p class="text-gray-400 text-sm mt-1">{{ $att->orderItem->ticketTier->name ?? '' }}</p>
                        <div class="mt-3 flex items-center justify-between">
                            <span class="text-xs text-gray-500">{{ $att->orderItem->order->event->start_at->format('d M Y') ?? '' }}</span>
                            @if($att->isCheckedIn())
                                <span class="text-xs bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-full">✓ Checked In</span>
                            @else
                                <span class="text-xs bg-purple-500/20 text-purple-400 px-2 py-0.5 rounded-full">Valid</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-6">{{ $attendees->links() }}</div>
        @endif
    </div>
</x-app-layout>
