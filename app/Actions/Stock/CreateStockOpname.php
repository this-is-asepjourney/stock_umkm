<?php

namespace App\Actions\Stock;

use App\Enums\OpnameStatus;
use App\Models\Product;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use Illuminate\Support\Facades\DB;

class CreateStockOpname
{
    /**
     * Membuat sesi stock opname baru beserta generasi itemnya
     * berdasarkan filter kategori/lokasi.
     *
     * @param  array{category_id?: int|null, location_id?: int|null, opname_date?: string|null, notes?: string|null, user_id?: int|null}  $data
     */
    public function execute(array $data = []): StockOpname
    {
        return DB::transaction(function () use ($data) {
            $opname = StockOpname::create([
                'user_id' => $data['user_id'] ?? auth()->id(),
                'category_id' => $data['category_id'] ?? null,
                'location_id' => $data['location_id'] ?? null,
                'opname_date' => $data['opname_date'] ?? now()->toDateString(),
                'status' => OpnameStatus::DRAFT->value,
                'notes' => $data['notes'] ?? null,
            ]);

            $this->generateItems($opname);

            return $opname->fresh('items');
        });
    }

    protected function generateItems(StockOpname $opname): void
    {
        $query = Product::query()->where('is_active', true);

        if ($opname->category_id) {
            $query->where('category_id', $opname->category_id);
        }

        $products = $query->get();

        $rows = $products->map(fn (Product $product) => [
            'stock_opname_id' => $opname->id,
            'product_id' => $product->id,
            'system_qty' => (int) $product->current_stock,
            'unit_cost' => (float) $product->cost_price,
            'is_counted' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        if (! empty($rows)) {
            StockOpnameItem::insert($rows);
            $opname->update(['total_items' => count($rows)]);
        }
    }
}
