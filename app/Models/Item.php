<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'category_id',
        'unit_id',
        'photo',
        'min_stock',
        'current_stock',
        'current_value',
        'is_active',
    ];

    protected $casts = [
        'min_stock' => 'decimal:2',
        'current_stock' => 'decimal:2',
        'current_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        // Auto delete old photo when updating
        static::updating(function ($item) {
            if ($item->isDirty('photo') && $item->getOriginal('photo')) {
                Storage::disk('public')->delete($item->getOriginal('photo'));
            }
        });

        // Auto delete photo when deleting item
        static::deleting(function ($item) {
            if ($item->photo) {
                Storage::disk('public')->delete($item->photo);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function stockBatches(): HasMany
    {
        return $this->hasMany(StockBatch::class);
    }

    public function stockAdjustments(): HasMany
    {
        return $this->hasMany(StockAdjustment::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? Storage::disk('public')->url($this->photo) : null;
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->min_stock;
    }
}
