<?php

namespace App\Services;

use App\Models\Attendee;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class TicketService
{
    /**
     * Process payment (mock) and generate attendees + ticket codes.
     */
    public function processPayment(Order $order, string $paymentMethod = 'mock_gateway'): Order
    {
        if (!$order->isPending()) {
            throw new \Exception('Order tidak dalam status pending.');
        }

        return DB::transaction(function () use ($order, $paymentMethod) {
            $order->update([
                'status'         => 'paid',
                'payment_method' => $paymentMethod,
                'paid_at'        => now(),
            ]);

            // Generate attendees for each order item
            foreach ($order->items as $item) {
                for ($i = 0; $i < $item->qty; $i++) {
                    Attendee::create([
                        'order_item_id' => $item->id,
                        'full_name'     => $order->user->name,
                        'email'         => $order->user->email,
                        'phone'         => $order->user->phone,
                    ]);
                }
            }

            return $order->fresh(['items.ticketTier', 'attendees', 'event', 'user']);
        });
    }

    /**
     * Check in an attendee by ticket code.
     *
     * @return array{success: bool, message: string, attendee: ?Attendee}
     */
    public function checkIn(string $ticketCode): array
    {
        $attendee = Attendee::where('ticket_code', $ticketCode)
            ->with('orderItem.ticketTier', 'orderItem.order.event')
            ->first();

        if (!$attendee) {
            return ['success' => false, 'message' => 'Kode tiket tidak ditemukan.', 'attendee' => null];
        }

        // Verify order is paid
        $order = $attendee->orderItem->order;
        if (!$order->isPaid()) {
            return ['success' => false, 'message' => 'Order belum dibayar (status: ' . $order->status . ').', 'attendee' => $attendee];
        }

        // Check if already scanned
        if ($attendee->isCheckedIn()) {
            return [
                'success'  => false,
                'message'  => 'Tiket sudah di-check-in pada ' . $attendee->checkin_at->format('d M Y H:i') . '.',
                'attendee' => $attendee,
            ];
        }

        $attendee->checkIn();

        return ['success' => true, 'message' => 'Check-in berhasil! ✅', 'attendee' => $attendee->fresh()];
    }
}
