<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\TicketService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request, Event $event, OrderService $orderService)
    {
        $request->validate([
            'tiers'           => 'required|array|min:1',
            'tiers.*.tier_id' => 'required|exists:ticket_tiers,id',
            'tiers.*.qty'     => 'required|integer|min:1',
            'promo_code'      => 'nullable|string|max:32',
        ]);

        // Filter out zero-qty tiers
        $items = collect($request->tiers)->filter(fn($t) => ($t['qty'] ?? 0) > 0)->values()->toArray();

        if (empty($items)) {
            return back()->with('error', 'Pilih minimal 1 tiket.');
        }

        try {
            $order = $orderService->createOrder(auth()->user(), $event, $items, $request->promo_code);
            return redirect()->route('checkout.summary', $order);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function summary(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('event', 'items.ticketTier', 'user');

        // Check if already expired
        if ($order->isExpirable()) {
            app(OrderService::class)->expireOrder($order);
            return redirect()->route('events.show', $order->event->slug)
                ->with('error', 'Order sudah expired. Silakan order ulang.');
        }

        return view('checkout.summary', compact('order'));
    }

    public function pay(Order $order, TicketService $ticketService)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if (!$order->isPending()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Order tidak dalam status pending.');
        }

        // Check expiry
        if ($order->isExpirable()) {
            app(OrderService::class)->expireOrder($order);
            return redirect()->route('events.show', $order->event->slug)
                ->with('error', 'Order sudah expired. Silakan order ulang.');
        }

        try {
            $ticketService->processPayment($order, 'mock_gateway');
            return redirect()->route('checkout.success', $order);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('event', 'items.ticketTier', 'attendees');

        return view('checkout.success', compact('order'));
    }
}
