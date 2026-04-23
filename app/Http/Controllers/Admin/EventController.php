<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('organizer', 'category');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $events = $query->latest()->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function updateStatus(Request $request, Event $event)
    {
        $request->validate(['status' => 'required|in:draft,published,ended,cancelled']);
        $event->update(['status' => $request->status]);

        return back()->with('success', "Status event diupdate ke \"{$request->status}\".");
    }
}
