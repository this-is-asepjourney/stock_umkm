<?php

namespace App\Actions\Purchase;

use App\Actions\Stock\RecordStockMovement;
use App\Enums\MovementType;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class ReceiveGoodsAction
{
    public function __construct(
        protected RecordStockMovement $recordMovement,
    ) {
    }

    /**
     * Catat penerimaan barang (bisa parsial) untuk sebuah Purchase Order.
     *
     * @param  array<int, int>  $receivedQuantities  key: purchase_order_item_id, value: qty diterima
     */
    public function execute(PurchaseOrder $purchaseOrder, array $receivedQuantities): PurchaseOrder
    {
        return DB::transaction(function () use ($purchaseOrder, $receivedQuantities) {
            $purchaseOrder->loadMissing('items.product');

            foreach ($purchaseOrder->items as $item) {
                $qty = (int) ($receivedQuantities[$item->id] ?? 0);
                if ($qty <= 0) {
                    continue;
                }

                $remaining = (int) $item->quantity_ordered - (int) $item->quantity_received;
                if ($qty > $remaining) {
                    throw new \DomainException(
                        "Qty diterima ({$qty}) melebihi sisa order ({$remaining}) untuk produk {$item->product->code}."
                    );
                }

                $item->quantity_received = (int) $item->quantity_received + $qty;
                $item->save();

                $this->recordMovement->execute(
                    product: $item->product,
                    quantity: $qty,
                    type: MovementType::IN,
                    reference: $purchaseOrder,
                    locationId: $purchaseOrder->location_id,
                    costPrice: (float) $item->unit_price,
                    notes: "Penerimaan PO {$purchaseOrder->po_number}",
                );
            }

            $totalOrdered = $purchaseOrder->items->sum('quantity_ordered');
            $totalReceived = $purchaseOrder->items->sum('quantity_received');

            $purchaseOrder->status = match (true) {
                $totalReceived <= 0 => $purchaseOrder->status,
                $totalReceived >= $totalOrdered => 'received',
                default => 'partial',
            };
            $purchaseOrder->save();

            return $purchaseOrder->fresh('items.product');
        });
    }
}
