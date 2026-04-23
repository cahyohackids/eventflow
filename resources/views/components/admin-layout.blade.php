@php $sidebarLinks = [
    ['route' => 'admin.dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
    ['route' => 'admin.events.index', 'icon' => 'calendar', 'label' => 'Events'],
    ['route' => 'admin.users.index', 'icon' => 'users', 'label' => 'Users'],
    ['route' => 'admin.categories.index', 'icon' => 'tag', 'label' => 'Kategori'],
    ['route' => 'admin.reports', 'icon' => 'bar-chart-3', 'label' => 'Reports'],
]; @endphp

<x-panel-layout :header="$header ?? 'Admin'" panelName="Admin Panel">
    <x-slot:sidebar>
        @foreach($sidebarLinks as $link)
            <a href="{{ route($link['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition {{ request()->routeIs($link['route'] . '*') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                <i data-lucide="{{ $link['icon'] }}" class="w-4 h-4"></i> {{ $link['label'] }}
            </a>
        @endforeach
        <hr class="my-3 border-gray-100">
        <a href="{{ route('organizer.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition"><i data-lucide="briefcase" class="w-4 h-4"></i> Organizer</a>
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition"><i data-lucide="globe" class="w-4 h-4"></i> Website</a>
    </x-slot:sidebar>
    {{ $slot }}
</x-panel-layout>
