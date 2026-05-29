<?php

namespace App\Actions\Stock;

use App\Enums\MovementType;
use App\Models\StockAdjustment;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdjustStock
{
    public function __construct(
        protected RecordStockMovement $recordMovement,
    ) {
    }

    /**
     * Terapkan penyesuaian stok dari hasil stock opname.
     * Membuat StockAdjustment + StockMovement bertipe ADJUSTMENT untuk setiap item bermasalah.
     *
     * @return int jumlah adjustment yang diterapkan
     */
    public function applyFromOpname(StockOpname $opname, ?int $approvedBy = null): int
    {
        return DB::transaction(function () use ($opname, $approvedBy) {
            $applied = 0;

            $items = $opname->items()
                ->where('is_counted', true)
                ->where('discrepancy', '!=', 0)
                ->with('product')
                ->get();

            foreach ($items as $item) {
                $this->applyItem($item, $opname, $approvedBy);
                $applied++;
            }

            return $applied;
        });
    }

    public function applyItem(
        StockOpnameItem $item,
        StockOpname $opname,
        ?int $approvedBy = null,
    ): StockAdjustment {
        $type = $item->discrepancy > 0 ? 'increase' : 'decrease';
        $before = (int) $item->system_qty;
        $after = (int) $item->physical_qty;

        $adjustment = StockAdjustment::create([
            'stock_opname_item_id' => $item->id,
            'product_id' => $item->product_id,
            'user_id' => Auth::id() ?? $opname->user_id,
            'approved_by' => $approvedBy,
            'approved_at' => $approvedBy ? now() : null,
            'type' => $type,
            'quantity_before' => $before,
            'quantity_adjusted' => abs((int) $item->discrepancy),
            'quantity_after' => $after,
            'reason' => "Penyesuaian stock opname {$opname->opname_number}",
        ]);

        $this->recordMovement->execute(
            product: $item->product,
            quantity: $after,
            type: MovementType::ADJUSTMENT,
            reference: $opname,
            costPrice: (float) $item->unit_cost,
            notes: "Opname {$opname->opname_number}: selisih {$item->discrepancy}",
        );

        return $adjustment;
    }
}
