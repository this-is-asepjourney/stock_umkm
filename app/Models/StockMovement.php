<?php

namespace App\Models;

use App\Enums\MovementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use \App\Traits\BelongsToCompany;

    protected $fillable = [
        'company_id',
        'product_id',
        'location_id',
        'user_id',
        'reference_type',
        'reference_id',
        'type',
        'quantity_before',
        'quantity',
        'quantity_after',
        'cost_price',
        'notes'
    ];

    protected $casts = [
        'quantity_before' => 'integer',
        'quantity' => 'integer',
        'quantity_after' => 'integer',
        'cost_price' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }

    // Scope untuk filter
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }

    public function typeEnum(): ?MovementType
    {
        return MovementType::tryFrom((string) $this->type);
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->typeEnum()?->label() ?? (string) $this->type;
    }

    public function getTypeColorAttribute(): string
    {
        return $this->typeEnum()?->color() ?? 'slate';
    }
}