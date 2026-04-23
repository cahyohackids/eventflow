<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Attendee extends Model
{
    protected $fillable = ['order_item_id', 'full_name', 'email', 'phone', 'ticket_code', 'checkin_at'];

    protected function casts(): array
    {
        return [
            'checkin_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Attendee $attendee) {
            if (empty($attendee->ticket_code)) {
                do {
                    $code = 'TKT-' . strtoupper(Str::random(8));
                } while (static::where('ticket_code', $code)->exists());
                $attendee->ticket_code = $code;
            }
        });
    }

    // ── Relationships ────────────────────────────────────

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function order()
    {
        return $this->hasOneThrough(Order::class, OrderItem::class, 'id', 'id', 'order_item_id', 'order_id');
    }

    // ── Helpers ──────────────────────────────────────────

    public function isCheckedIn(): bool
    {
        return $this->checkin_at !== null;
    }

    public function checkIn(): bool
    {
        if ($this->isCheckedIn()) return false;

        $this->update(['checkin_at' => now()]);
        return true;
    }
}
