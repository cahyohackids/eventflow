<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('category', 'ticketTiers', 'organizer')->published();

        // Search
        if ($request->filled('q')) {
            $s = $request->q;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('city', 'like', "%{$s}%")
                  ->orWhere('venue_name', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        // Filter: category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter: city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filter: date
        if ($request->filled('date_from')) {
            $query->where('start_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('start_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Filter: free only
        if ($request->boolean('free')) {
            $query->whereHas('ticketTiers', fn($q) => $q->where('price', 0));
        }

        // Sort
        $sort = $request->input('sort', 'date');
        $query = match ($sort) {
            'popular' => $query->withCount(['orders' => fn($q) => $q->where('status', 'paid')])->orderByDesc('orders_count'),
            'price'   => $query->orderByRaw('(SELECT MIN(price) FROM ticket_tiers WHERE event_id = events.id)'),
            default   => $query->orderBy('start_at'),
        };

        $events = $query->paginate(12)->withQueryString();

        $categories = Category::orderBy('name')->get();
        $cities = Event::published()->whereNotNull('city')->distinct()->pluck('city');

        return view('events.index', compact('events', 'categories', 'cities'));
    }

    public function show(string $slug)
    {
        $event = Event::where('slug', $slug)
            ->published()
            ->with(['category', 'organizer', 'ticketTiers'])
            ->firstOrFail();

        return view('events.show', compact('event'));
    }
}
