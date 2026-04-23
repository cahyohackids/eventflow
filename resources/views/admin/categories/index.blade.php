<x-admin-layout header="Kategori">
    {{-- Add --}}
    <form method="POST" action="{{ route('admin.categories.store') }}" class="flex gap-3 mb-6">
        @csrf
        <input type="text" name="name" placeholder="Nama kategori baru..." required class="border border-gray-200 rounded-xl px-4 py-2 text-sm flex-1">
        <button class="bg-purple-600 text-white px-4 py-2 rounded-xl text-sm font-medium">+ Tambah</button>
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead><tr class="bg-gray-50 text-gray-500 border-b"><th class="px-5 py-3">Nama</th><th class="px-5 py-3">Slug</th><th class="px-5 py-3">Events</th><th class="px-5 py-3">Aksi</th></tr></thead>
            <tbody>
            @foreach($categories as $cat)
                <tr class="border-b border-gray-50">
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.categories.update', $cat) }}" class="flex gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="name" value="{{ $cat->name }}" class="border border-gray-200 rounded-lg px-2 py-1 text-sm w-40">
                            <button class="text-purple-600 text-xs hover:underline">Save</button>
                        </form>
                    </td>
                    <td class="px-5 py-3 text-gray-400">{{ $cat->slug }}</td>
                    <td class="px-5 py-3">{{ $cat->events_count }}</td>
                    <td class="px-5 py-3">
                        @if($cat->events_count == 0)
                        <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="text-red-500 text-xs hover:underline">Hapus</button></form>
                        @else <span class="text-xs text-gray-400">In use</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-admin-layout>
