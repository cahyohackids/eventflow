@php $sidebarLinks = [
    ['route' => 'organizer.dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
    ['route' => 'organizer.events.index', 'icon' => 'calendar', 'label' => 'Kelola Event'],
    ['route' => 'organizer.scanner', 'icon' => 'scan-line', 'label' => 'Scanner'],
]; @endphp

<x-panel-layout :header="$header ?? 'Organizer'" panelName="Organizer Panel">
    <x-slot:sidebar>
        @foreach($sidebarLinks as $link)
            <a href="{{ route($link['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm transition {{ request()->routeIs($link['route'] . '*') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                <i data-lucide="{{ $link['icon'] }}" class="w-4 h-4"></i> {{ $link['label'] }}
            </a>
        @endforeach
        <hr class="my-3 border-gray-100">
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition">
            <i data-lucide="globe" class="w-4 h-4"></i> Lihat Website
        </a>
    </x-slot:sidebar>

    {{ $slot }}
</x-panel-layout>
