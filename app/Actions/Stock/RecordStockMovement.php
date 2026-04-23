<?php

namespace App\Actions\Stock;

use App\Enums\MovementType;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RecordStockMovement
{
    /**
     * Catat pergerakan stok + perbarui current_stock produk.
     * Dilakukan dalam transaksi tunggal agar atomik.
     */
    public function execute(
        Product $product,
        int $quantity,
        MovementType $type,
        ?Model $reference = null,
        ?int $locationId = null,
        ?float $costPrice = null,
        ?string $notes = null,
        ?int $userId = null,
    ): StockMovement {
        return DB::transaction(function () use (
            $product,
            $quantity,
            $type,
            $reference,
            $locationId,
            $costPrice,
            $notes,
            $userId,
        ) {
            $product = Product::query()->lockForUpdate()->findOrFail($product->id);
            $before = (int) $product->current_stock;

            $after = match ($type) {
                MovementType::IN => $before + $quantity,
                MovementType::OUT => $before - $quantity,
                MovementType::ADJUSTMENT, MovementType::OPNAME => $quantity,
            };

            if ($after < 0) {
                throw new \DomainException(
                    "Stok tidak mencukupi untuk produk {$product->code}. Tersedia: {$before}, diminta: {$quantity}."
                );
            }

            $product->current_stock = $after;
            $product->save();

            return StockMovement::create([
                'product_id' => $product->id,
                'location_id' => $locationId,
                'user_id' => $userId ?? auth()->id(),
                'reference_type' => $reference ? $reference::class : 'manual',
                'reference_id' => $reference?->getKey() ?? 0,
                'type' => $type->value,
                'quantity_before' => $before,
                'quantity' => $quantity,
                'quantity_after' => $after,
                'cost_price' => $costPrice ?? $product->cost_price,
                'notes' => $notes,
            ]);
        });
    }
}
