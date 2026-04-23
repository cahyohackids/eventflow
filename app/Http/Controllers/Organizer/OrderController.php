<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Event $event)
    {
        $this->authorizeOrganizer($event);

        $orders = Order::where('event_id', $event->id)
            ->with('user', 'items.ticketTier')
            ->latest()
            ->paginate(20);

        return view('organizer.orders.index', compact('event', 'orders'));
    }

    public function show(Event $event, Order $order)
    {
        $this->authorizeOrganizer($event);
        abort_unless($order->event_id === $event->id, 404);

        $order->load('user', 'items.ticketTier', 'attendees');

        return view('organizer.orders.show', compact('event', 'order'));
    }

    public function exportCsv(Event $event): StreamedResponse
    {
        $this->authorizeOrganizer($event);

        $attendees = Attendee::whereHas('orderItem.order', function ($q) use ($event) {
            $q->where('event_id', $event->id)->where('status', 'paid');
        })->with('orderItem.ticketTier', 'orderItem.order')->get();

        $filename = 'attendees-' . $event->slug . '-' . now()->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($attendees) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'Nama', 'Email', 'Telepon', 'Ticket Tier', 'Ticket Code', 'Checked In']);

            foreach ($attendees as $i => $att) {
                fputcsv($handle, [
                    $i + 1,
                    $att->full_name,
                    $att->email,
                    $att->phone ?? '-',
                    $att->orderItem->ticketTier->name,
                    $att->ticket_code,
                    $att->checkin_at ? $att->checkin_at->format('d/m/Y H:i') : 'Belum',
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function authorizeOrganizer(Event $event): void
    {
        if ($event->organizer_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
    }
}
