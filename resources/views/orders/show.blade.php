<x-app-layout :title="'Order ' . $order->order_code">
    <div class="max-w-3xl mx-auto px-4 py-10">
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-xl font-bold">{{ $order->event->title }}</h1>
                    <p class="text-gray-400 text-sm">{{ $order->order_code }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ match($order->status) { 'paid' => 'bg-emerald-500/20 text-emerald-400', 'pending' => 'bg-yellow-500/20 text-yellow-400', 'expired' => 'bg-gray-500/20 text-gray-400', 'refunded' => 'bg-blue-500/20 text-blue-400', default => 'bg-red-500/20 text-red-400' } }}">{{ ucfirst($order->status) }}</span>
            </div>

            {{-- Items --}}
            <div class="space-y-2 mb-4">
                @foreach($order->items as $item)
                    <div class="flex justify-between text-sm border-b border-white/5 pb-2">
                        <span>{{ $item->ticketTier->name }} × {{ $item->qty }}</span>
                        <span>Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-white/10 pt-3 space-y-1 text-sm">
                <div class="flex justify-between"><span class="text-gray-400">Subtotal</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Fee</span><span>Rp {{ number_format($order->fee, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span class="text-gray-400">Tax</span><span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span></div>
                @if($order->discount > 0)
                <div class="flex justify-between text-emerald-400"><span>Diskon</span><span>-Rp {{ number_format($order->discount, 0, ',', '.') }}</span></div>
                @endif
                <div class="flex justify-between font-bold text-lg pt-2 border-t border-white/10"><span>Total</span><span class="text-purple-400">Rp {{ number_format($order->total, 0, ',', '.') }}</span></div>
            </div>

            {{-- Attendees --}}
            @if($order->attendees->isNotEmpty())
            <div class="mt-6">
                <h3 class="font-semibold mb-3">E-Tickets</h3>
                @foreach($order->attendees as $att)
                    <div class="bg-white/5 rounded-xl p-3 mb-2 flex justify-between items-center">
                        <div>
                            <span class="font-mono text-sm text-purple-400">{{ $att->ticket_code }}</span>
                            <span class="text-xs text-gray-500 ml-2">{{ $att->orderItem->ticketTier->name ?? '' }}</span>
                        </div>
                        <a href="{{ route('tickets.show', $att) }}" class="text-sm text-purple-400 hover:underline">Lihat</a>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- Cancel --}}
            @if($order->isPaid())
            <form method="POST" action="{{ route('orders.cancel', $order) }}" class="mt-6" onsubmit="return confirm('Yakin ingin membatalkan order ini?')">
                @csrf
                <button class="text-sm text-red-400 hover:text-red-300 border border-red-500/30 px-4 py-2 rounded-xl transition">Batalkan & Refund</button>
            </form>
            @endif
        </div>
    </div>
</x-app-layout>
