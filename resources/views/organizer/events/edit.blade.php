<x-organizer-layout header="Edit Event">
    <div class="max-w-3xl">
        {{-- Event Form --}}
        <form method="POST" action="{{ route('organizer.events.update', $event) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5 mb-6">
                <div class="grid sm:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Judul *</label><input type="text" name="title" value="{{ old('title', $event->title) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500">@error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label><select name="category_id" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"><option value="">Pilih</option>@foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach</select></div>
                </div>
                <div class="grid sm:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Mulai *</label><input type="datetime-local" name="start_at" value="{{ old('start_at', $event->start_at->format('Y-m-d\TH:i')) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Selesai *</label><input type="datetime-local" name="end_at" value="{{ old('end_at', $event->end_at->format('Y-m-d\TH:i')) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"></div>
                </div>
                <div class="grid sm:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Venue</label><input type="text" name="venue_name" value="{{ old('venue_name', $event->venue_name) }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Kota</label><input type="text" name="city" value="{{ old('city', $event->city) }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"></div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label><input type="text" name="venue_address" value="{{ old('venue_address', $event->venue_address) }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label><textarea name="description" rows="5" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">{{ old('description', $event->description) }}</textarea></div>
                <div class="grid sm:grid-cols-2 gap-5">
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Status</label><select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">@foreach(['draft','published','ended','cancelled'] as $s)<option value="{{ $s }}" {{ old('status', $event->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-medium text-gray-700 mb-1">Banner</label><input type="file" name="banner" accept="image/*" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">@if($event->banner)<p class="text-xs text-gray-400 mt-1">Sudah ada banner</p>@endif</div>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Syarat</label><textarea name="terms" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">{{ old('terms', $event->terms) }}</textarea></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Kebijakan Refund</label><textarea name="refund_policy" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">{{ old('refund_policy', $event->refund_policy) }}</textarea></div>
                <label class="flex items-center gap-2"><input type="checkbox" name="is_online" value="1" {{ old('is_online', $event->is_online) ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600"> <span class="text-sm text-gray-700">Event Online</span></label>
            </div>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">Update Event</button>
        </form>

        {{-- Ticket Tiers --}}
        <div class="mt-10">
            <h2 class="text-lg font-semibold mb-4">🎫 Ticket Tiers</h2>

            @foreach($event->ticketTiers as $tier)
            <div class="bg-white rounded-2xl border border-gray-100 p-5 mb-3">
                <form method="POST" action="{{ route('organizer.tiers.update', [$event, $tier]) }}" class="grid sm:grid-cols-6 gap-3 items-end">
                    @csrf @method('PUT')
                    <div><label class="text-xs text-gray-500">Nama</label><input type="text" name="name" value="{{ $tier->name }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" required></div>
                    <div><label class="text-xs text-gray-500">Harga</label><input type="number" name="price" value="{{ $tier->price }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" min="0" required></div>
                    <div><label class="text-xs text-gray-500">Kuota</label><input type="number" name="quota" value="{{ $tier->quota }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" min="{{ $tier->sold_count }}" required></div>
                    <div><label class="text-xs text-gray-500">Max/Order</label><input type="number" name="max_per_order" value="{{ $tier->max_per_order }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" min="1" required></div>
                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-1"><input type="checkbox" name="is_refundable" value="1" {{ $tier->is_refundable ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600"> <span class="text-xs">Refund</span></label>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg text-xs transition">Save</button>
                        @if($tier->sold_count == 0)
                        <button type="submit" form="delete-tier-{{ $tier->id }}" class="text-red-500 hover:bg-red-50 px-3 py-2 rounded-lg text-xs transition">Del</button>
                        @endif
                    </div>
                </form>
                <form id="delete-tier-{{ $tier->id }}" method="POST" action="{{ route('organizer.tiers.destroy', [$event, $tier]) }}">@csrf @method('DELETE')</form>
                <p class="text-xs text-gray-400 mt-2">Terjual: {{ $tier->sold_count }}/{{ $tier->quota }}</p>
            </div>
            @endforeach

            {{-- Add Tier --}}
            <form method="POST" action="{{ route('organizer.tiers.store', $event) }}" class="bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 p-5 mt-4">
                @csrf
                <p class="text-sm font-medium text-gray-700 mb-3">+ Tambah Tier Baru</p>
                <div class="grid sm:grid-cols-5 gap-3 items-end">
                    <div><label class="text-xs text-gray-500">Nama *</label><input type="text" name="name" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" placeholder="VIP"></div>
                    <div><label class="text-xs text-gray-500">Harga *</label><input type="number" name="price" required min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" placeholder="100000"></div>
                    <div><label class="text-xs text-gray-500">Kuota *</label><input type="number" name="quota" required min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" placeholder="100"></div>
                    <div><label class="text-xs text-gray-500">Max/Order</label><input type="number" name="max_per_order" value="5" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"></div>
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</x-organizer-layout>
