<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'organizer_id', 'category_id', 'title', 'slug', 'description', 'banner',
        'venue_name', 'venue_address', 'city', 'is_online',
        'start_at', 'end_at', 'status', 'terms', 'refund_policy',
    ];

    protected function casts(): array
    {
        return [
            'start_at'  => 'datetime',
            'end_at'    => 'datetime',
            'is_online' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            if (empty($event->slug)) {
                $base = Str::slug($event->title);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $event->slug = $slug;
            }
        });
    }

    // ── Relationships ────────────────────────────────────

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ticketTiers()
    {
        return $this->hasMany(TicketTier::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ── Scopes ───────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->published()->where('start_at', '>=', now());
    }

    // ── Helpers ──────────────────────────────────────────

    public function getMinPrice(): float
    {
        return $this->ticketTiers->min('price') ?? 0;
    }

    public function getTotalQuota(): int
    {
        return $this->ticketTiers->sum('quota');
    }

    public function getTotalSold(): int
    {
        return $this->ticketTiers->sum('sold_count');
    }

    public function getAvailableStock(): int
    {
        return $this->getTotalQuota() - $this->getTotalSold();
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
