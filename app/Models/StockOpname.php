<?php

namespace App\Models;

use App\Enums\OpnameStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $location_id
 * @property int|null $category_id
 * @property string $opname_number
 * @property \Illuminate\Support\Carbon|null $opname_date
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property string $status
 * @property int $total_items
 * @property int $total_discrepancy
 * @property float $total_discrepancy_value
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class StockOpname extends Model
{
    protected $fillable = [
        'user_id',
        'location_id',
        'category_id',
        'opname_number',
        'opname_date',
        'started_at',
        'completed_at',
        'status',
        'total_items',
        'total_discrepancy',
        'total_discrepancy_value',
        'notes'
    ];

    protected $casts = [
        'opname_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_discrepancy_value' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($opname) {
            if (!$opname->opname_number) {
                $opname->opname_number = self::generateNumber();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockOpnameItem::class);
    }

    public static function generateNumber(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', now())->count() + 1;
        return 'OPN-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function statusEnum(): ?OpnameStatus
    {
        return OpnameStatus::tryFrom((string) $this->status);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->statusEnum()?->label() ?? (string) $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return $this->statusEnum()?->color() ?? 'slate';
    }

    // Mulai opname
    public function start(): void
    {
        $this->status = OpnameStatus::IN_PROGRESS->value;
        $this->started_at = now();
        $this->save();
    }

    // Selesaikan opname
    public function complete(): void
    {
        $this->status = OpnameStatus::COMPLETED->value;
        $this->completed_at = now();

        // Hitung total
        $this->total_items = $this->items()->count();
        $this->total_discrepancy = (int) $this->items()->sum('discrepancy');
        $this->total_discrepancy_value = (float) $this->items()->sum('discrepancy_value');

        $this->save();
    }

    // Generate items untuk opname
    public function generateItems(): void
    {
        $query = Product::where('is_active', true);

        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        $products = $query->get();

        foreach ($products as $product) {
            StockOpnameItem::create([
                'stock_opname_id' => $this->id,
                'product_id' => $product->id,
                'system_qty' => $product->current_stock,
                'unit_cost' => $product->cost_price,
            ]);
        }
    }
}