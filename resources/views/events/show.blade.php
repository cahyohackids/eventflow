<x-app-layout :title="$event->title">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Banner --}}
                <div class="aspect-video bg-gradient-to-br from-purple-900/50 to-indigo-900/50 rounded-2xl overflow-hidden flex items-center justify-center">
                    @if($event->banner)
                        <img src="{{ Storage::url($event->banner) }}" class="w-full h-full object-cover">
                    @else
                        <i data-lucide="image" class="w-16 h-16 text-purple-400/40"></i>
                    @endif
                </div>

                {{-- Info --}}
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6 space-y-4">
                    @if($event->category)
                        <span class="text-xs font-medium text-purple-400 bg-purple-500/10 px-3 py-1 rounded-full">{{ $event->category->name }}</span>
                    @endif
                    <h1 class="text-3xl font-bold">{{ $event->title }}</h1>

                    <div class="grid sm:grid-cols-2 gap-4 text-sm text-gray-400">
                        <div class="flex items-center gap-2"><i data-lucide="calendar" class="w-4 h-4 text-purple-400"></i> {{ $event->start_at->format('d M Y, H:i') }} — {{ $event->end_at->format('H:i') }}</div>
                        <div class="flex items-center gap-2"><i data-lucide="map-pin" class="w-4 h-4 text-purple-400"></i> {{ $event->venue_name ?? ($event->is_online ? 'Online Event' : '-') }}</div>
                        @if($event->venue_address)<div class="flex items-center gap-2"><i data-lucide="navigation" class="w-4 h-4 text-purple-400"></i> {{ $event->venue_address }}</div>@endif
                        <div class="flex items-center gap-2"><i data-lucide="user" class="w-4 h-4 text-purple-400"></i> {{ $event->organizer->name }}</div>
                    </div>
                </div>

                {{-- Description --}}
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-4">Deskripsi</h2>
                    <div class="text-gray-300 leading-relaxed whitespace-pre-line">{{ $event->description }}</div>
                </div>

                {{-- Terms --}}
                @if($event->terms)
                <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-4">Syarat & Ketentuan</h2>
                    <div class="text-gray-400 text-sm whitespace-pre-line">{{ $event->terms }}</div>
                </div>
                @endif
            </div>

            {{-- Sidebar: Buy Tickets --}}
            <div class="lg:col-span-1">
                <div class="sticky top-20 bg-white/5 border border-white/10 rounded-2xl p-6">
                    <h2 class="text-xl font-semibold mb-4">🎫 Beli Tiket</h2>

                    @auth
                    <form method="POST" action="{{ route('checkout.store', $event) }}">
                        @csrf
                        <div class="space-y-4">
                            @foreach($event->ticketTiers as $i => $tier)
                                <div class="border border-white/10 rounded-xl p-4 {{ !$tier->isOnSale() ? 'opacity-50' : '' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h4 class="font-semibold">{{ $tier->name }}</h4>
                                            <p class="text-purple-400 font-bold text-lg">
                                                @if($tier->price == 0) Gratis @else Rp {{ number_format($tier->price, 0, ',', '.') }} @endif
                                            </p>
                                        </div>
                                        @if($tier->is_refundable)
                                            <span class="text-xs bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-full">Refundable</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 mb-2">Tersisa {{ $tier->availableStock() }} tiket · Max {{ $tier->max_per_order }}/order</p>

                                    <input type="hidden" name="tiers[{{ $i }}][tier_id]" value="{{ $tier->id }}">
                                    @if($tier->isOnSale())
                                        <select name="tiers[{{ $i }}][qty]" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white">
                                            <option value="0">0 tiket</option>
                                            @for($q = 1; $q <= min($tier->max_per_order, $tier->availableStock()); $q++)
                                                <option value="{{ $q }}">{{ $q }} tiket</option>
                                            @endfor
                                        </select>
                                    @else
                                        <p class="text-xs text-red-400">Tidak tersedia</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Promo Code --}}
                        <div class="mt-4">
                            <input type="text" name="promo_code" placeholder="Kode promo (opsional)" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder:text-gray-500">
                        </div>

                        <button type="submit" class="w-full mt-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 py-3 rounded-xl font-semibold transition">
                            Pesan Sekarang
                        </button>
                    </form>
                    @else
                        <p class="text-gray-400 text-sm mb-4">Login terlebih dahulu untuk membeli tiket.</p>
                        <a href="{{ route('login') }}" class="block text-center bg-purple-600 hover:bg-purple-700 py-3 rounded-xl font-semibold transition">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
