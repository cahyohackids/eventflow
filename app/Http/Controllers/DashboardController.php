<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $activeTickets = $user->orders()
            ->where('status', 'paid')
            ->withCount('attendees')
            ->get()
            ->sum('attendees_count');

        $totalOrders = $user->orders()->count();

        $recentOrders = $user->orders()
            ->with('event')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('activeTickets', 'totalOrders', 'recentOrders'));
    }
}
