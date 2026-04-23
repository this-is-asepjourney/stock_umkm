<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_item_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->constrained();
            $table->foreignId('user_id')->constrained(); // Who created
            $table->foreignId('approved_by')->nullable()->constrained('users');

            $table->enum('type', ['increase', 'decrease']);

            $table->integer('quantity_before');
            $table->integer('quantity_adjusted');
            $table->integer('quantity_after');

            $table->text('reason');
            $table->datetime('approved_at')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_adjustments');
    }
};