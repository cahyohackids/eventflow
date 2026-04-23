<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTier extends Model
{
    protected $fillable = [
        'event_id', 'name', 'price', 'quota', 'sold_count',
        'sales_start', 'sales_end', 'max_per_order', 'is_refundable',
    ];

    protected function casts(): array
    {
        return [
            'price'         => 'decimal:2',
            'sales_start'   => 'datetime',
            'sales_end'     => 'datetime',
            'is_refundable' => 'boolean',
        ];
    }

    // ── Relationships ────────────────────────────────────

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Helpers ──────────────────────────────────────────

    public function availableStock(): int
    {
        return max(0, $this->quota - $this->sold_count);
    }

    public function isOnSale(): bool
    {
        $now = now();

        if ($this->sales_start && $now->lt($this->sales_start)) return false;
        if ($this->sales_end && $now->gt($this->sales_end)) return false;
        if ($this->availableStock() <= 0) return false;

        return true;
    }
}
