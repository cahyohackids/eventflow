<x-app-layout title="Ringkasan Order">
    <div class="max-w-2xl mx-auto px-4 py-10">
        <div class="bg-white/5 border border-white/10 rounded-2xl p-8">
            <h1 class="text-2xl font-bold mb-2">Ringkasan Order</h1>
            <p class="text-gray-400 text-sm mb-6">Kode: <span class="font-mono text-purple-400">{{ $order->order_code }}</span></p>

            @if($order->isPending() && $order->expires_at)
                <div class="bg-yellow-500/10 border border-yellow-500/30 text-yellow-300 px-4 py-3 rounded-xl text-sm mb-6">
                    ⏳ Selesaikan pembayaran sebelum <strong>{{ $order->expires_at->format('d M Y H:i') }}</strong> atau order akan otomatis expired.
                </div>
            @endif

            {{-- Event --}}
            <div class="border-b border-white/10 pb-4 mb-4">
                <h3 class="font-semibold text-lg">{{ $order->event->title }}</h3>
                <p class="text-gray-400 text-sm">{{ $order->event->start_at->format('d M Y, H:i') }} · {{ $order->event->venue_name ?? 'Online' }}</p>
            </div>

            {{-- Items --}}
            <div class="space-y-3 mb-6">
                @foreach($order->items as $item)
                    <div class="flex justify-between text-sm">
                        <span>{{ $item->ticketTier->name }} × {{ $item->qty }}</span>
                        <span>Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Totals --}}
            <div class="border-t border-white/10 pt-4 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-400">Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Service Fee (3%)</span><span>Rp {{ number_format($order->fee, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">PPN (11%)</span><span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span></div>
                @if($order->discount > 0)
                    <div class="flex justify-between text-emerald-400"><span>Diskon</span><span>-Rp {{ number_format($order->discount, 0, ',', '.') }}</span></div>
                @endif
                <div class="flex justify-between font-bold text-lg pt-2 border-t border-white/10"><span>Total</span><span class="text-purple-400">Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>
            </div>

            {{-- Pay Button --}}
            @if($order->isPending())
                <form method="POST" action="{{ route('checkout.pay', $order) }}" class="mt-6">
                    @csrf
                    <button class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 py-3.5 rounded-xl font-bold text-lg transition">
                        💳 Bayar Sekarang — Rp {{ number_format($order->total, 0, ',', '.') }}
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
