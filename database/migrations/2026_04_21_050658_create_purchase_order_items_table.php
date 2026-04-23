<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();

            $table->integer('quantity_ordered');
            $table->integer('quantity_received')->default(0);

            $table->decimal('unit_price', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2);

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_order_items');
    }
};