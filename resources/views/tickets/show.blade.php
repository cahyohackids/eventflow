<x-app-layout :title="'Tiket ' . $attendee->ticket_code">
    <div class="max-w-lg mx-auto px-4 py-10">
        <div class="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
            {{-- QR --}}
            <div class="bg-white p-8 flex items-center justify-center">
                <div class="text-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($attendee->ticket_code) }}" alt="QR Code" class="mx-auto w-48 h-48">
                    <p class="font-mono text-gray-800 font-bold text-lg mt-4">{{ $attendee->ticket_code }}</p>
                </div>
            </div>
            {{-- Info --}}
            <div class="p-6 space-y-3">
                <h2 class="text-xl font-bold">{{ $attendee->orderItem->order->event->title }}</h2>
                <div class="text-sm text-gray-400 space-y-1">
                    <p><i data-lucide="calendar" class="w-4 h-4 inline text-purple-400"></i> {{ $attendee->orderItem->order->event->start_at->format('d M Y, H:i') }}</p>
                    <p><i data-lucide="map-pin" class="w-4 h-4 inline text-purple-400"></i> {{ $attendee->orderItem->order->event->venue_name ?? 'Online' }}</p>
                    <p><i data-lucide="ticket" class="w-4 h-4 inline text-purple-400"></i> {{ $attendee->orderItem->ticketTier->name }}</p>
                    <p><i data-lucide="user" class="w-4 h-4 inline text-purple-400"></i> {{ $attendee->full_name }}</p>
                </div>

                @if($attendee->isCheckedIn())
                    <div class="bg-emerald-500/20 text-emerald-400 text-sm px-4 py-2 rounded-xl">✓ Checked in pada {{ $attendee->checkin_at->format('d M Y H:i') }}</div>
                @endif

                <a href="{{ route('tickets.pdf', $attendee) }}" class="block text-center bg-purple-600 hover:bg-purple-700 py-3 rounded-xl font-semibold transition mt-4">
                    📥 Download PDF
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
