<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $purchase_order_id
 * @property int $product_id
 * @property int $quantity_ordered
 * @property int $quantity_received
 * @property float $unit_price
 * @property float $discount
 * @property float $subtotal
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PurchaseOrderItem extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity_ordered',
        'quantity_received',
        'unit_price',
        'discount',
        'subtotal'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Hitung subtotal
    public function calculateSubtotal(): void
    {
        $this->subtotal = ($this->quantity_ordered * $this->unit_price) - $this->discount;
        $this->save();
    }

    // Terima barang
    public function receive(int $quantity): void
    {
        if ($quantity > ($this->quantity_ordered - $this->quantity_received)) {
            throw new \Exception('Quantity received exceeds ordered quantity');
        }

        $this->quantity_received += $quantity;
        $this->save();

        // Update stok produk
        $this->product->updateStock($quantity, 'IN', $this->purchaseOrder);
    }
}