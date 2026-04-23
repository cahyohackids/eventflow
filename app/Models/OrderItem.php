<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'ticket_tier_id', 'qty', 'unit_price', 'total'];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'total'      => 'decimal:2',
        ];
    }

    // ── Relationships ────────────────────────────────────

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketTier()
    {
        return $this->belongsTo(TicketTier::class);
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
}
