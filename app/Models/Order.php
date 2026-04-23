<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'event_id', 'order_code', 'subtotal', 'fee', 'tax',
        'discount', 'total', 'status', 'payment_method', 'paid_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'   => 'decimal:2',
            'fee'        => 'decimal:2',
            'tax'        => 'decimal:2',
            'discount'   => 'decimal:2',
            'total'      => 'decimal:2',
            'paid_at'    => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Order $order) {
            if (empty($order->order_code)) {
                $order->order_code = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }

    // ── Relationships ────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function attendees()
    {
        return $this->hasManyThrough(Attendee::class, OrderItem::class);
    }

    // ── Status Helpers ───────────────────────────────────

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isPaid(): bool      { return $this->status === 'paid'; }
    public function isExpired(): bool   { return $this->status === 'expired'; }
    public function isRefunded(): bool  { return $this->status === 'refunded'; }

    public function isExpirable(): bool
    {
        return $this->isPending() && $this->expires_at && now()->gte($this->expires_at);
    }
}
