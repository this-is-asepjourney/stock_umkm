<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');

            $table->string('opname_number')->unique();
            $table->date('opname_date');
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();

            $table->enum('status', ['draft', 'in_progress', 'completed', 'cancelled'])->default('draft');

            $table->text('notes')->nullable();
            $table->integer('total_items')->default(0);
            $table->integer('total_discrepancy')->default(0);

            $table->timestamps();

            $table->index('opname_number');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_opnames');
    }
};