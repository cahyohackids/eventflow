<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::where('user_id', auth()->id())->with('event');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('event', 'items.ticketTier', 'attendees');

        return view('orders.show', compact('order'));
    }

    public function cancel(Order $order, OrderService $orderService)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if (!$order->isPaid()) {
            return back()->with('error', 'Hanya order yang sudah dibayar yang bisa dibatalkan.');
        }

        $success = $orderService->cancelOrder($order);

        if (!$success) {
            return back()->with('error', 'Tidak bisa membatalkan order. Batas waktu refund sudah lewat (minimal 24 jam sebelum event).');
        }

        return back()->with('success', 'Order berhasil dibatalkan dan akan direfund.');
    }

    public function exportCsv(): StreamedResponse
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('event')
            ->latest()
            ->get();

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order Code', 'Event', 'Total', 'Status', 'Tanggal']);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_code,
                    $order->event->title,
                    'Rp ' . number_format($order->total, 0, ',', '.'),
                    $order->status,
                    $order->created_at->format('d/m/Y H:i'),
                ]);
            }
            fclose($handle);
        }, 'orders-' . now()->format('Ymd') . '.csv', ['Content-Type' => 'text/csv']);
    }
}
