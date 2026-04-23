<x-organizer-layout header="Buat Event Baru">
    <form method="POST" action="{{ route('organizer.events.store') }}" enctype="multipart/form-data" class="max-w-3xl">
        @csrf
        <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-5 mb-6">
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Event *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-purple-500 focus:border-purple-500">
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                    <select name="category_id" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-purple-500">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="grid sm:grid-cols-2 gap-5">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Mulai *</label><input type="datetime-local" name="start_at" value="{{ old('start_at') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-purple-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Selesai *</label><input type="datetime-local" name="end_at" value="{{ old('end_at') }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-purple-500"></div>
            </div>
            <div class="grid sm:grid-cols-2 gap-5">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Venue</label><input type="text" name="venue_name" value="{{ old('venue_name') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-purple-500"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Kota</label><input type="text" name="city" value="{{ old('city') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-purple-500"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label><input type="text" name="venue_address" value="{{ old('venue_address') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-purple-500"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi *</label><textarea name="description" rows="5" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-purple-500">{{ old('description') }}</textarea></div>
            <div class="grid sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-purple-500">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">Banner</label><input type="file" name="banner" accept="image/*" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Syarat & Ketentuan</label><textarea name="terms" rows="3" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-purple-500">{{ old('terms') }}</textarea></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">Kebijakan Refund</label><textarea name="refund_policy" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-purple-500">{{ old('refund_policy') }}</textarea></div>
            <label class="flex items-center gap-2"><input type="checkbox" name="is_online" value="1" {{ old('is_online') ? 'checked' : '' }} class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"> <span class="text-sm text-gray-700">Event Online</span></label>
        </div>
        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">Simpan Event</button>
    </form>
</x-organizer-layout>
