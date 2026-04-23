<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('locations')->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['warehouse', 'rack', 'bin']);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
};