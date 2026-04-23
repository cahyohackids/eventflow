<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        $featuredEvents = Event::with('category', 'ticketTiers', 'organizer')
            ->published()
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->take(6)
            ->get();

        $categories = Category::withCount(['events' => fn($q) => $q->published()])
            ->having('events_count', '>', 0)
            ->orderBy('name')
            ->get();

        $totalEvents = Event::published()->count();

        return view('welcome', compact('featuredEvents', 'categories', 'totalEvents'));
    }
}
