<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockBatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'transaction_id',
        'initial_quantity',
        'remaining_quantity',
        'unit_price',
        'batch_date',
    ];

    protected $casts = [
        'initial_quantity' => 'decimal:2',
        'remaining_quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'batch_date' => 'date',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function isExhausted(): bool
    {
        return $this->remaining_quantity <= 0;
    }

    public function getUsedQuantityAttribute(): float
    {
        return $this->initial_quantity - $this->remaining_quantity;
    }
}
