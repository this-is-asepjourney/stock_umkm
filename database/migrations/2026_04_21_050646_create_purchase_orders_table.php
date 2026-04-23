<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('location_id')->nullable()->constrained();

            $table->string('po_number')->unique();
            $table->date('po_date');
            $table->date('expected_date')->nullable();

            $table->enum('status', ['draft', 'ordered', 'partial', 'received', 'cancelled'])
                ->default('draft');

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('po_number');
            $table->index('status');
            $table->index('po_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchase_orders');
    }
};