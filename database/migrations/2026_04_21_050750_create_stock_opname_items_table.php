<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('stock_opname_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('counted_by')->nullable()->constrained('users')->onDelete('set null');

            $table->integer('system_qty');
            $table->integer('physical_qty')->nullable();
            $table->integer('discrepancy')->nullable();

            $table->decimal('unit_cost', 15, 2)->nullable();
            $table->decimal('discrepancy_value', 15, 2)->nullable();

            $table->text('notes')->nullable();
            $table->boolean('is_counted')->default(false);
            $table->datetime('counted_at')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_opname_items');
    }
};