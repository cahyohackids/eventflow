<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue  = Order::where('status', 'paid')->sum('total');
        $totalOrders   = Order::where('status', 'paid')->count();
        $totalEvents   = Event::count();
        $totalUsers    = User::count();
        $pendingOrders = Order::where('status', 'pending')->count();

        $recentOrders = Order::with('user', 'event')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'totalEvents', 'totalUsers', 'pendingOrders', 'recentOrders'
        ));
    }
}
