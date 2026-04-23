<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketTier;
use Illuminate\Http\Request;

class TicketTierController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $this->authorizeOrganizer($event);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'quota'          => 'required|integer|min:1',
            'max_per_order'  => 'required|integer|min:1|max:50',
            'is_refundable'  => 'boolean',
            'sales_start'    => 'nullable|date',
            'sales_end'      => 'nullable|date|after:sales_start',
        ]);

        $validated['is_refundable'] = $request->boolean('is_refundable');
        $validated['event_id'] = $event->id;

        TicketTier::create($validated);

        return back()->with('success', 'Ticket tier berhasil ditambahkan!');
    }

    public function update(Request $request, Event $event, TicketTier $tier)
    {
        $this->authorizeOrganizer($event);
        abort_unless($tier->event_id === $event->id, 404);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'quota'          => 'required|integer|min:' . $tier->sold_count,
            'max_per_order'  => 'required|integer|min:1|max:50',
            'is_refundable'  => 'boolean',
            'sales_start'    => 'nullable|date',
            'sales_end'      => 'nullable|date|after:sales_start',
        ]);

        $validated['is_refundable'] = $request->boolean('is_refundable');
        $tier->update($validated);

        return back()->with('success', 'Ticket tier berhasil diperbarui!');
    }

    public function destroy(Event $event, TicketTier $tier)
    {
        $this->authorizeOrganizer($event);
        abort_unless($tier->event_id === $event->id, 404);

        if ($tier->sold_count > 0) {
            return back()->with('error', 'Tidak bisa menghapus tier yang sudah terjual.');
        }

        $tier->delete();
        return back()->with('success', 'Ticket tier berhasil dihapus.');
    }

    private function authorizeOrganizer(Event $event): void
    {
        if ($event->organizer_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
    }
}
