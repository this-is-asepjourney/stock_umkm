<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $stock_opname_id
 * @property int $product_id
 * @property int|null $counted_by
 * @property int $system_qty
 * @property int|null $physical_qty
 * @property int|null $discrepancy
 * @property float $unit_cost
 * @property float|null $discrepancy_value
 * @property bool $is_counted
 * @property \Illuminate\Support\Carbon|null $counted_at
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class StockOpnameItem extends Model
{
    protected $fillable = [
        'stock_opname_id',
        'product_id',
        'counted_by',
        'system_qty',
        'physical_qty',
        'discrepancy',
        'unit_cost',
        'discrepancy_value',
        'is_counted',
        'counted_at',
        'notes'
    ];

    protected $casts = [
        'system_qty' => 'integer',
        'physical_qty' => 'integer',
        'discrepancy' => 'integer',
        'unit_cost' => 'decimal:2',
        'discrepancy_value' => 'decimal:2',
        'is_counted' => 'boolean',
        'counted_at' => 'datetime',
    ];

    public function stockOpname(): BelongsTo
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function countedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counted_by');
    }

    // Input hasil hitung fisik
    public function count(int $physicalQty, ?string $notes = null): void
    {
        $this->physical_qty = $physicalQty;
        $this->discrepancy = $physicalQty - $this->system_qty;
        $this->discrepancy_value = $this->discrepancy * $this->unit_cost;
        $this->is_counted = true;
        $this->counted_at = now();
        $this->counted_by = auth()->id();
        $this->notes = $notes;
        $this->save();
    }

    // Apply adjustment
    public function applyAdjustment(): void
    {
        if ($this->discrepancy != 0) {
            $adjustmentType = $this->discrepancy > 0 ? 'increase' : 'decrease';

            StockAdjustment::create([
                'stock_opname_item_id' => $this->id,
                'product_id' => $this->product_id,
                'user_id' => auth()->id(),
                'type' => $adjustmentType,
                'quantity_before' => $this->system_qty,
                'quantity_adjusted' => abs($this->discrepancy),
                'quantity_after' => $this->physical_qty,
                'reason' => "Stock opname adjustment: {$this->stockOpname->opname_number}",
            ]);

            // Update stok produk
            $this->product->updateStock($this->physical_qty, 'ADJUSTMENT', $this->stockOpname);
        }
    }
}