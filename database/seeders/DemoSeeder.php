<?php

namespace Database\Seeders;

use App\Actions\Purchase\ReceiveGoodsAction;
use App\Actions\Stock\RecordStockMovement;
use App\Enums\MovementType;
use App\Models\Location;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // User is already created and authenticated in DatabaseSeeder
        $user = auth()->user();

        $supplier = Supplier::query()->first();
        $location = Location::query()->first();
        $products = Product::query()->limit(3)->get();

        if ($products->isEmpty() || ! $supplier) {
            $this->command?->warn('Skip DemoSeeder: pastikan Product & Supplier sudah ada.');
            return;
        }

        $this->seedPurchase($products, $supplier, $location, $user);
        $this->seedSale($products, $location, $user);
    }

    protected function seedPurchase($products, Supplier $supplier, ?Location $location, User $user): void
    {
        DB::transaction(function () use ($products, $supplier, $location, $user) {
            $po = PurchaseOrder::create([
                'supplier_id' => $supplier->id,
                'user_id' => $user->id,
                'location_id' => $location?->id,
                'po_date' => now()->subDays(3)->toDateString(),
                'expected_date' => now()->subDays(1)->toDateString(),
                'status' => 'ordered',
                'notes' => 'Seed demo Purchase Order',
            ]);

            foreach ($products as $product) {
                $qty = 20;
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $product->id,
                    'quantity_ordered' => $qty,
                    'quantity_received' => 0,
                    'unit_price' => $product->cost_price,
                    'discount' => 0,
                    'subtotal' => $product->cost_price * $qty,
                ]);
            }

            $po->refresh();
            $po->subtotal = $po->items->sum('subtotal');
            $po->total = $po->subtotal;
            $po->save();

            $receive = app(ReceiveGoodsAction::class);
            $receive->execute(
                $po,
                $po->items->mapWithKeys(fn ($i) => [$i->id => $i->quantity_ordered])->all(),
            );
        });
    }

    protected function seedSale($products, ?Location $location, User $user): void
    {
        DB::transaction(function () use ($products, $location, $user) {
            $sale = Sale::create([
                'user_id' => $user->id,
                'sale_date' => now()->toDateString(),
                'customer_name' => 'Pelanggan Demo',
                'customer_phone' => '081200000000',
                'status' => 'completed',
                'payment_method' => 'cash',
                'subtotal' => 0,
                'discount' => 0,
                'tax' => 0,
                'total' => 0,
                'notes' => 'Seed demo Sale',
            ]);

            $subtotal = 0;
            $mover = app(RecordStockMovement::class);

            foreach ($products as $product) {
                $qty = 2;
                $lineTotal = $product->selling_price * $qty;
                $subtotal += $lineTotal;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_price' => $product->selling_price,
                    'discount' => 0,
                    'subtotal' => $lineTotal,
                ]);

                $mover->execute(
                    product: $product,
                    quantity: $qty,
                    type: MovementType::OUT,
                    reference: $sale,
                    locationId: $location?->id,
                    costPrice: (float) $product->cost_price,
                    notes: "Penjualan demo {$sale->id}",
                );
            }

            $sale->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);
        });
    }
}
