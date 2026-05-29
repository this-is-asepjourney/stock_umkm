<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('location_id')->nullable()->constrained();
            $table->foreignId('user_id')->constrained();

            $table->string('reference_type'); // purchase, sale, adjustment, opname
            $table->unsignedBigInteger('reference_id');

            $table->enum('type', ['IN', 'OUT', 'ADJUSTMENT']);

            $table->integer('quantity_before');
            $table->integer('quantity');
            $table->integer('quantity_after');

            $table->decimal('cost_price', 15, 2)->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
};