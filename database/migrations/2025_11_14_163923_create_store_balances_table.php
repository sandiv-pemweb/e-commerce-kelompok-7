<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('store_balances', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->decimal('balance', 26, 2);
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('store_balances');
    }
};
