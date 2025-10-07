<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAdjustment extends Model
{
    protected $fillable = [
        'item_id',
        'old_quantity',
        'new_quantity',
        'adjustment_quantity',
        'reason',
        'user_id',
        'adjustment_date',
    ];

    protected $casts = [
        'old_quantity' => 'decimal:2',
        'new_quantity' => 'decimal:2',
        'adjustment_quantity' => 'decimal:2',
        'adjustment_date' => 'date',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isIncrease(): bool
    {
        return $this->adjustment_quantity > 0;
    }

    public function isDecrease(): bool
    {
        return $this->adjustment_quantity < 0;
    }
}
