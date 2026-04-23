<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Console\Command;

class ExpirePendingOrders extends Command
{
    protected $signature = 'orders:expire-pending';
    protected $description = 'Expire pending orders that have passed their expiry time and restore stock';

    public function handle(OrderService $orderService): int
    {
        $orders = Order::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            $orderService->expireOrder($order);
            $count++;
        }

        $this->info("Expired {$count} pending orders.");
        return Command::SUCCESS;
    }
}
