<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $supplier_id
 * @property int $user_id
 * @property int|null $location_id
 * @property string $po_number
 * @property \Illuminate\Support\Carbon|null $po_date
 * @property \Illuminate\Support\Carbon|null $expected_date
 * @property string $status
 * @property float $subtotal
 * @property float $discount
 * @property float $tax
 * @property float $total
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PurchaseOrder extends Model
{
    use \App\Traits\BelongsToCompany;

    protected $fillable = [
        'company_id',
        'supplier_id',
        'user_id',
        'location_id',
        'po_number',
        'po_date',
        'expected_date',
        'status',
        'subtotal',
        'discount',
        'tax',
        'total',
        'notes'
    ];

    protected $casts = [
        'po_date' => 'date',
        'expected_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($po) {
            if (!$po->po_number) {
                $po->po_number = self::generateNumber();
            }
        });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public static function generateNumber(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return 'PO-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // Hitung total
    public function calculateTotal(): void
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->total = $this->subtotal - $this->discount + $this->tax;
        $this->save();
    }

    // Terima barang
    public function receiveItems(array $receivedQuantities): void
    {
        foreach ($this->items as $item) {
            if (isset($receivedQuantities[$item->id])) {
                $qty = $receivedQuantities[$item->id];
                if ($qty > 0) {
                    $item->receive($qty);
                }
            }
        }

        // Update status
        $totalOrdered = $this->items->sum('quantity_ordered');
        $totalReceived = $this->items->sum('quantity_received');

        if ($totalReceived >= $totalOrdered) {
            $this->status = 'received';
        } elseif ($totalReceived > 0) {
            $this->status = 'partial';
        }

        $this->save();
    }
}