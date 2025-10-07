<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;
    const TYPE_IN = 'masuk';
    const TYPE_OUT = 'keluar';

    protected $fillable = [
        'item_id',
        'type',
        'quantity',
        'unit_price',
        'total_value',
        'reference_no',
        'notes',
        'supplier_id',
        'user_id',
        'transaction_date',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_value' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }

    public function isIncoming(): bool
    {
        return $this->type === self::TYPE_IN;
    }

    public function isOutgoing(): bool
    {
        return $this->type === self::TYPE_OUT;
    }
}
