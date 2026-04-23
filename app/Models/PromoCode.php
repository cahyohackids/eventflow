<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = ['code', 'type', 'value', 'start_at', 'end_at', 'usage_limit', 'used_count'];

    protected function casts(): array
    {
        return [
            'value'    => 'decimal:2',
            'start_at' => 'datetime',
            'end_at'   => 'datetime',
        ];
    }

    // ── Helpers ──────────────────────────────────────────

    public function isValid(): bool
    {
        $now = now();

        if ($this->start_at && $now->lt($this->start_at)) return false;
        if ($this->end_at && $now->gt($this->end_at)) return false;
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) return false;

        return true;
    }

    public function applyDiscount(float $subtotal): float
    {
        if (!$this->isValid()) return 0;

        if ($this->type === 'percent') {
            return round($subtotal * ($this->value / 100), 2);
        }

        return min($this->value, $subtotal);
    }
}
