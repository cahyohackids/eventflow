<x-app-layout title="Pembayaran Berhasil!">
    <div class="max-w-2xl mx-auto px-4 py-10 text-center">
        <div class="bg-white/5 border border-white/10 rounded-2xl p-10">
            <div class="w-20 h-20 bg-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="check-circle" class="w-10 h-10 text-emerald-400"></i>
            </div>
            <h1 class="text-3xl font-bold mb-2">Pembayaran Berhasil! 🎉</h1>
            <p class="text-gray-400 mb-6">Order <span class="font-mono text-purple-400">{{ $order->order_code }}</span></p>

            <div class="bg-white/5 rounded-xl p-5 mb-6 text-left">
                <h3 class="font-semibold mb-2">{{ $order->event->title }}</h3>
                <p class="text-gray-400 text-sm">{{ $order->event->start_at->format('d M Y, H:i') }}</p>
                <p class="text-emerald-400 font-bold mt-2">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            </div>

            @if($order->attendees->isNotEmpty())
                <div class="text-left mb-6">
                    <h3 class="font-semibold mb-3">E-Ticket Kamu</h3>
                    @foreach($order->attendees as $att)
                        <div class="bg-white/5 border border-white/10 rounded-xl p-4 mb-2 flex justify-between items-center">
                            <div>
                                <p class="font-mono text-sm text-purple-400">{{ $att->ticket_code }}</p>
                                <p class="text-xs text-gray-400">{{ $att->orderItem->ticketTier->name ?? '' }}</p>
                            </div>
                            <a href="{{ route('tickets.show', $att) }}" class="text-sm text-purple-400 hover:underline">Lihat →</a>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('tickets.index') }}" class="bg-purple-600 hover:bg-purple-700 px-6 py-2.5 rounded-xl text-sm font-medium transition">Lihat Tiket Saya</a>
                <a href="{{ route('events.index') }}" class="border border-white/20 hover:bg-white/5 px-6 py-2.5 rounded-xl text-sm font-medium transition">Jelajahi Event Lain</a>
            </div>
        </div>
    </div>
</x-app-layout>
