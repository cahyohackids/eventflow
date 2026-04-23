<?php

namespace App\Services;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PromoCode;
use App\Models\TicketTier;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    const FEE_RATE  = 0.03;  // 3% service fee
    const TAX_RATE  = 0.11;  // 11% PPN
    const EXPIRY_MINUTES = 15;

    /**
     * Create a pending order with race-condition-safe stock deduction.
     *
     * @param User   $user
     * @param Event  $event
     * @param array  $items  [['tier_id' => int, 'qty' => int], ...]
     * @param string|null $promoCode
     * @return Order
     *
     * @throws \Exception
     */
    public function createOrder(User $user, Event $event, array $items, ?string $promoCode = null): Order
    {
        return DB::transaction(function () use ($user, $event, $items, $promoCode) {

            $subtotal = 0;
            $orderItems = [];

            foreach ($items as $item) {
                // Lock the tier row to prevent oversell
                $tier = TicketTier::where('id', $item['tier_id'])
                    ->where('event_id', $event->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if (!$tier->isOnSale()) {
                    throw new \Exception("Tiket \"{$tier->name}\" tidak tersedia untuk dijual saat ini.");
                }

                $qty = (int) $item['qty'];

                if ($qty > $tier->max_per_order) {
                    throw new \Exception("Maksimal {$tier->max_per_order} tiket per order untuk \"{$tier->name}\".");
                }

                if ($tier->availableStock() < $qty) {
                    throw new \Exception("Kuota tiket \"{$tier->name}\" tidak mencukupi. Sisa: {$tier->availableStock()}.");
                }

                // Deduct stock
                $tier->increment('sold_count', $qty);

                $lineTotal = $tier->price * $qty;
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'tier'       => $tier,
                    'qty'        => $qty,
                    'unit_price' => $tier->price,
                    'total'      => $lineTotal,
                ];
            }

            // Calculate fees
            $fee  = round($subtotal * self::FEE_RATE, 2);
            $tax  = round($subtotal * self::TAX_RATE, 2);
            $discount = 0;

            // Apply promo code
            if ($promoCode) {
                $promo = PromoCode::where('code', $promoCode)->first();
                if ($promo && $promo->isValid()) {
                    $discount = $promo->applyDiscount($subtotal);
                    $promo->increment('used_count');
                }
            }

            $total = max(0, $subtotal + $fee + $tax - $discount);

            // Create order
            $order = Order::create([
                'user_id'    => $user->id,
                'event_id'   => $event->id,
                'subtotal'   => $subtotal,
                'fee'        => $fee,
                'tax'        => $tax,
                'discount'   => $discount,
                'total'      => $total,
                'status'     => 'pending',
                'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
            ]);

            // Create order items
            foreach ($orderItems as $oi) {
                OrderItem::create([
                    'order_id'       => $order->id,
                    'ticket_tier_id' => $oi['tier']->id,
                    'qty'            => $oi['qty'],
                    'unit_price'     => $oi['unit_price'],
                    'total'          => $oi['total'],
                ]);
            }

            return $order->load('items.ticketTier', 'event');
        });
    }

    /**
     * Expire a pending order and restore stock.
     */
    public function expireOrder(Order $order): void
    {
        if (!$order->isPending()) return;

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'expired']);

            foreach ($order->items as $item) {
                $item->ticketTier->decrement('sold_count', $item->qty);
            }
        });
    }

    /**
     * Cancel/refund an order if the event's refund policy allows.
     */
    public function cancelOrder(Order $order): bool
    {
        if (!$order->isPaid()) return false;

        // Check refund deadline: must be at least 24h before event start
        $event = $order->event;
        if ($event->start_at->subDay()->isPast()) return false;

        DB::transaction(function () use ($order) {
            $order->update(['status' => 'refunded']);

            foreach ($order->items as $item) {
                $item->ticketTier->decrement('sold_count', $item->qty);
                $item->attendees()->delete();
            }
        });

        return true;
    }
}
