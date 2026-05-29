<?php

namespace App\Models;

use App\Enums\StockStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Traits\BelongsToCompany;

class Product extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'category_id',
        'unit_id',
        'supplier_id',
        'code',
        'barcode',
        'name',
        'slug',
        'description',
        'image',
        'cost_price',
        'selling_price',
        'min_stock',
        'max_stock',
        'current_stock',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (!$product->code) {
                $product->code = self::generateCode();
            }
            $product->slug = Str::slug($product->name);
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
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

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockOpnameItems(): HasMany
    {
        return $this->hasMany(StockOpnameItem::class);
    }

    // Generate kode produk otomatis
    public static function generateCode(): string
    {
        $lastProduct = self::latest('id')->first();
        $lastId = $lastProduct ? $lastProduct->id + 1 : 1;
        return 'PRD-' . str_pad($lastId, 6, '0', STR_PAD_LEFT);
    }

    // Cek status stok
    public function getStockStatusAttribute(): string
    {
        return $this->stockStatusEnum()->value;
    }

    public function stockStatusEnum(): StockStatus
    {
        return StockStatus::fromQuantity(
            (int) $this->current_stock,
            (int) ($this->min_stock ?? 0),
        );
    }

    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'min_stock')
            ->where('current_stock', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    // Update stok
    public function updateStock(int $quantity, string $type = 'IN', $reference = null): void
    {
        $quantityBefore = $this->current_stock;

        if ($type === 'IN') {
            $this->current_stock += $quantity;
        } elseif ($type === 'OUT') {
            $this->current_stock -= $quantity;
        } elseif ($type === 'ADJUSTMENT') {
            $this->current_stock = $quantity;
        }

        $this->save();

        // Record stock movement
        StockMovement::create([
            'product_id' => $this->id,
            'user_id' => auth()->id(),
            'reference_type' => $reference ? get_class($reference) : 'manual',
            'reference_id' => $reference ? $reference->id : null,
            'type' => $type,
            'quantity_before' => $quantityBefore,
            'quantity' => $quantity,
            'quantity_after' => $this->current_stock,
            'cost_price' => $this->cost_price,
        ]);
    }
}