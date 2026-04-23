<x-app-layout title="Jelajahi Event">
    <div class="max-w-7xl mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold mb-8">Jelajahi Event</h1>

        {{-- Filters --}}
        <form method="GET" action="{{ route('events.index') }}" class="bg-white/5 border border-white/10 rounded-2xl p-5 mb-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari event..." class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder:text-gray-500 focus:border-purple-500 focus:ring-purple-500">
                <select name="category" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:border-purple-500">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="sort" class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:border-purple-500">
                    <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Terdekat</option>
                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                    <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Harga Terendah</option>
                </select>
                <button class="bg-purple-600 hover:bg-purple-700 rounded-xl px-4 py-2.5 text-sm font-medium transition">Cari</button>
            </div>
        </form>

        {{-- Events Grid --}}
        @if($events->isEmpty())
            <div class="text-center py-20">
                <i data-lucide="calendar-x" class="w-16 h-16 text-gray-600 mx-auto mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-400">Tidak Ada Event Ditemukan</h3>
                <p class="text-gray-500 mt-2">Coba ubah filter pencarian Anda.</p>
            </div>
        @else
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <a href="{{ route('events.show', $event->slug) }}"
                       class="group bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:border-purple-500/50 transition-all duration-300">
                        <div class="aspect-video bg-gradient-to-br from-purple-900/50 to-indigo-900/50 flex items-center justify-center">
                            @if($event->banner)
                                <img src="{{ Storage::url($event->banner) }}" class="w-full h-full object-cover">
                            @else
                                <i data-lucide="calendar" class="w-12 h-12 text-purple-400/50"></i>
                            @endif
                        </div>
                        <div class="p-5">
                            @if($event->category)
                                <span class="text-xs font-medium text-purple-400 bg-purple-500/10 px-2.5 py-1 rounded-full">{{ $event->category->name }}</span>
                            @endif
                            <h3 class="text-lg font-semibold mt-3 group-hover:text-purple-400 transition">{{ $event->title }}</h3>
                            <div class="mt-3 space-y-1 text-sm text-gray-400">
                                <p><i data-lucide="calendar" class="w-4 h-4 inline"></i> {{ $event->start_at->format('d M Y, H:i') }}</p>
                                <p><i data-lucide="map-pin" class="w-4 h-4 inline"></i> {{ $event->venue_name ?? ($event->is_online ? 'Online' : $event->city) }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-white/5 flex justify-between items-center">
                                <span class="text-purple-400 font-bold">
                                    @if($event->getMinPrice() == 0) Gratis @else Rp {{ number_format($event->getMinPrice(), 0, ',', '.') }} @endif
                                </span>
                                <span class="text-xs text-gray-500">{{ $event->getAvailableStock() }} sisa</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8">{{ $events->links() }}</div>
        @endif
    </div>
</x-app-layout>
