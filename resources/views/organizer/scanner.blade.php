<x-organizer-layout header="Scanner Check-In">
    <div class="max-w-lg mx-auto">
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-4">🔍 Scan Tiket</h2>
            <form method="POST" action="{{ route('organizer.scanner.checkin') }}">
                @csrf
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Tiket</label>
                <input type="text" name="ticket_code" required autofocus placeholder="TKT-XXXXXXXX" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-lg font-mono focus:ring-purple-500 focus:border-purple-500">
                <button type="submit" class="w-full mt-4 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-xl font-medium transition">Scan</button>
            </form>
        </div>

        @if(session('scan_success'))
            <div class="mt-4 bg-emerald-50 border border-emerald-200 rounded-2xl p-5">
                <p class="text-emerald-700 font-semibold text-lg">{{ session('scan_success') }}</p>
                @if(session('scan_attendee'))
                    @php $att = session('scan_attendee'); @endphp
                    <div class="mt-3 text-sm text-emerald-600 space-y-1">
                        <p><strong>Nama:</strong> {{ $att->full_name }}</p>
                        <p><strong>Kode:</strong> {{ $att->ticket_code }}</p>
                        <p><strong>Check-in:</strong> {{ $att->checkin_at?->format('d M Y H:i') }}</p>
                    </div>
                @endif
            </div>
        @endif

        @if(session('scan_error'))
            <div class="mt-4 bg-red-50 border border-red-200 rounded-2xl p-5">
                <p class="text-red-700 font-semibold">{{ session('scan_error') }}</p>
                @if(session('scan_attendee'))
                    @php $att = session('scan_attendee'); @endphp
                    <div class="mt-3 text-sm text-red-600 space-y-1">
                        <p><strong>Nama:</strong> {{ $att->full_name ?? '-' }}</p>
                        <p><strong>Kode:</strong> {{ $att->ticket_code ?? '-' }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-organizer-layout>
