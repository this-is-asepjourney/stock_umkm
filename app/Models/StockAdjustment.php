<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $stock_opname_item_id
 * @property int $product_id
 * @property int $user_id
 * @property int|null $approved_by
 * @property string $type
 * @property int $quantity_before
 * @property int $quantity_adjusted
 * @property int $quantity_after
 * @property string $reason
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class StockAdjustment extends Model
{
    protected $fillable = [
        'stock_opname_item_id',
        'product_id',
        'user_id',
        'approved_by',
        'type',
        'quantity_before',
        'quantity_adjusted',
        'quantity_after',
        'reason',
        'approved_at'
    ];

    protected $casts = [
        'quantity_before' => 'integer',
        'quantity_adjusted' => 'integer',
        'quantity_after' => 'integer',
        'approved_at' => 'datetime',
    ];

    public function stockOpnameItem(): BelongsTo
    {
        return $this->belongsTo(StockOpnameItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Approve adjustment
    public function approve(): void
    {
        $this->approved_by = auth()->id();
        $this->approved_at = now();
        $this->save();
    }
}