<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;

class Auction extends Model
{
    /**
     * Status constants — auctions.status kolonu için enum değerleri.
     * Migration comment'iyle tutarlı: pending, approved, active, paused, closed, rejected, cancelled.
     */
    public const STATUS_PENDING   = 'pending';
    public const STATUS_APPROVED  = 'approved';
    public const STATUS_ACTIVE    = 'active';
    public const STATUS_PAUSED    = 'paused';
    public const STATUS_CLOSED    = 'closed';
    public const STATUS_REJECTED  = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'product_id',
        'title',
        'description',
        'image',
        'start_at',
        'end_at',
        'start_price',
        'current_price',
        'min_price',
        'buy_now_price',
        'bid_increment',
        'min_increment',
        'is_buy_now_available',
        'status',
        'winner_customer_id',
        'closed_at',
    ];

    protected $casts = [
        'start_at'             => 'datetime',
        'end_at'               => 'datetime',
        'closed_at'            => 'datetime',
        'start_price'          => 'decimal:4',
        'current_price'        => 'decimal:4',
        'min_price'            => 'decimal:4',
        'buy_now_price'        => 'decimal:4',
        'bid_increment'        => 'decimal:4',
        'min_increment'        => 'decimal:4',
        'is_buy_now_available' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'user_id', 'id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'winner_customer_id', 'id');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class, 'auction_id', 'id');
    }

    public function getStartAtFormattedAttribute(): ?string
    {
        return $this->start_at?->translatedFormat('d F Y H:i');
    }

    public function getEndAtFormattedAttribute(): ?string
    {
        return $this->end_at?->translatedFormat('d F Y H:i');
    }

    public function isLive(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && $this->start_at && $this->start_at->isPast()
            && $this->end_at && $this->end_at->isFuture();
    }

    public function isUpcoming(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED], true)
            && $this->start_at && $this->start_at->isFuture();
    }

    public function isClosed(): bool
    {
        return in_array($this->status, [self::STATUS_CLOSED, self::STATUS_CANCELLED, self::STATUS_REJECTED], true)
            || ($this->end_at && $this->end_at->isPast());
    }
}
