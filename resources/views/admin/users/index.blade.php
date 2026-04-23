<x-admin-layout header="Users">
    <form method="GET" class="flex gap-3 mb-6">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama/email..." class="border border-gray-200 rounded-xl px-4 py-2 text-sm flex-1">
        <select name="role" class="border border-gray-200 rounded-xl px-4 py-2 text-sm" onchange="this.form.submit()">
            <option value="">Semua Role</option>
            @foreach(['admin','organizer','customer'] as $r)<option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>@endforeach
        </select>
        <button class="bg-purple-600 text-white px-4 py-2 rounded-xl text-sm">Filter</button>
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead><tr class="bg-gray-50 text-gray-500 border-b"><th class="px-5 py-3">Nama</th><th class="px-5 py-3">Email</th><th class="px-5 py-3">Role</th><th class="px-5 py-3">Join</th><th class="px-5 py-3">Aksi</th></tr></thead>
            <tbody>
            @foreach($users as $user)
                <tr class="border-b border-gray-50">
                    <td class="px-5 py-3 font-medium">{{ $user->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->email }}</td>
                    <td class="px-5 py-3">
                        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="role" class="border border-gray-200 rounded-lg px-2 py-1 text-xs">
                                @foreach(['admin','organizer','customer'] as $r)<option value="{{ $r }}" {{ $user->role == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>@endforeach
                            </select>
                            <button class="text-purple-600 text-xs hover:underline">Save</button>
                        </form>
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus user ini?')">@csrf @method('DELETE')
                            <button class="text-red-500 text-xs hover:underline">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</x-admin-layout>
