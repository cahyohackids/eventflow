<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Monthly revenue (last 6 months)
        $monthlyRevenue = Order::where('status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw("DATE_FORMAT(paid_at, '%Y-%m') as month"),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top events by revenue
        $topEvents = Event::withSum(['orders as revenue' => fn($q) => $q->where('status', 'paid')], 'total')
            ->withCount(['orders as paid_orders' => fn($q) => $q->where('status', 'paid')])
            ->orderByDesc('revenue')
            ->take(10)
            ->get();

        // Order status breakdown
        $statusBreakdown = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.reports', compact('monthlyRevenue', 'topEvents', 'statusBreakdown'));
    }
}
