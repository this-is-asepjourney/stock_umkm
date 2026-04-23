<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $sale_id
 * @property int $product_id
 * @property int $quantity
 * @property float $unit_price
 * @property float $discount
 * @property float $subtotal
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount',
        'subtotal'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function calculateSubtotal(): void
    {
        $this->subtotal = ($this->quantity * $this->unit_price) - $this->discount;
        $this->save();
    }
}