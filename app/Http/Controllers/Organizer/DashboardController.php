<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $events = $user->events()->withCount('orders')->get();

        $totalRevenue   = Order::whereIn('event_id', $events->pluck('id'))
            ->where('status', 'paid')->sum('total');
        $totalOrders    = Order::whereIn('event_id', $events->pluck('id'))
            ->where('status', 'paid')->count();
        $totalSold      = $events->sum(fn ($e) => $e->ticketTiers->sum('sold_count'));
        $activeEvents   = $events->where('status', 'published')->count();

        $recentOrders = Order::whereIn('event_id', $events->pluck('id'))
            ->with('user', 'event')
            ->latest()
            ->take(10)
            ->get();

        return view('organizer.dashboard', compact(
            'events', 'totalRevenue', 'totalOrders', 'totalSold', 'activeEvents', 'recentOrders'
        ));
    }
}
