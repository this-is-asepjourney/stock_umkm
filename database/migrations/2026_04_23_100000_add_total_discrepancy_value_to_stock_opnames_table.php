<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('stock_opnames', function (Blueprint $table) {
            if (! Schema::hasColumn('stock_opnames', 'total_discrepancy_value')) {
                $table->decimal('total_discrepancy_value', 15, 2)->default(0)->after('total_discrepancy');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_opnames', function (Blueprint $table) {
            if (Schema::hasColumn('stock_opnames', 'total_discrepancy_value')) {
                $table->dropColumn('total_discrepancy_value');
            }
        });
    }
};
