<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('location_id')->nullable()->constrained();

            $table->string('sale_number')->unique();
            $table->date('sale_date');

            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            $table->enum('payment_method', ['cash', 'transfer', 'qris', 'other'])->default('cash');
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('sale_number');
            $table->index('sale_date');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
};