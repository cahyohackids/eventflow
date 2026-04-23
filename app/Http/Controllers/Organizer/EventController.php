<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = auth()->user()->events()
            ->with('category', 'ticketTiers')
            ->latest()
            ->paginate(10);

        return view('organizer.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('organizer.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'required|string',
            'venue_name'     => 'nullable|string|max:255',
            'venue_address'  => 'nullable|string|max:500',
            'city'           => 'nullable|string|max:100',
            'is_online'      => 'boolean',
            'start_at'       => 'required|date|after:now',
            'end_at'         => 'required|date|after:start_at',
            'status'         => 'required|in:draft,published',
            'banner'         => 'nullable|image|max:2048',
            'terms'          => 'nullable|string',
            'refund_policy'  => 'nullable|string',
        ]);

        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('banners', 'public');
        }

        $validated['organizer_id'] = auth()->id();
        $validated['is_online'] = $request->boolean('is_online');

        $event = Event::create($validated);

        return redirect()->route('organizer.events.edit', $event)
            ->with('success', 'Event berhasil dibuat! Sekarang tambahkan tiket tier.');
    }

    public function edit(Event $event)
    {
        $this->authorizeOrganizer($event);
        $categories = Category::orderBy('name')->get();
        $event->load('ticketTiers');

        return view('organizer.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeOrganizer($event);

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'required|string',
            'venue_name'     => 'nullable|string|max:255',
            'venue_address'  => 'nullable|string|max:500',
            'city'           => 'nullable|string|max:100',
            'is_online'      => 'boolean',
            'start_at'       => 'required|date',
            'end_at'         => 'required|date|after:start_at',
            'status'         => 'required|in:draft,published,ended,cancelled',
            'banner'         => 'nullable|image|max:2048',
            'terms'          => 'nullable|string',
            'refund_policy'  => 'nullable|string',
        ]);

        if ($request->hasFile('banner')) {
            if ($event->banner) Storage::disk('public')->delete($event->banner);
            $validated['banner'] = $request->file('banner')->store('banners', 'public');
        }

        $validated['is_online'] = $request->boolean('is_online');

        $event->update($validated);

        return redirect()->route('organizer.events.index')
            ->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event)
    {
        $this->authorizeOrganizer($event);

        if ($event->orders()->where('status', 'paid')->exists()) {
            return back()->with('error', 'Event tidak bisa dihapus karena sudah memiliki order yang dibayar.');
        }

        if ($event->banner) Storage::disk('public')->delete($event->banner);
        $event->delete();

        return redirect()->route('organizer.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    private function authorizeOrganizer(Event $event): void
    {
        if ($event->organizer_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }
    }
}
