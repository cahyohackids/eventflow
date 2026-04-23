<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\Order;
use Illuminate\Http\Request;

class MyTicketsController extends Controller
{
    public function index()
    {
        $attendees = Attendee::whereHas('orderItem.order', function ($q) {
            $q->where('user_id', auth()->id())->where('status', 'paid');
        })
        ->with('orderItem.ticketTier', 'orderItem.order.event')
        ->latest()
        ->paginate(12);

        return view('tickets.index', compact('attendees'));
    }

    public function show(Attendee $attendee)
    {
        abort_unless($attendee->orderItem->order->user_id === auth()->id(), 403);
        $attendee->load('orderItem.ticketTier', 'orderItem.order.event');

        return view('tickets.show', compact('attendee'));
    }

    public function downloadPdf(Attendee $attendee)
    {
        abort_unless($attendee->orderItem->order->user_id === auth()->id(), 403);
        $attendee->load('orderItem.ticketTier', 'orderItem.order.event');

        $pdf = app('dompdf.wrapper')
            ->loadView('tickets.pdf', compact('attendee'))
            ->setPaper('a5', 'landscape');

        return $pdf->download('ticket-' . $attendee->ticket_code . '.pdf');
    }
}
