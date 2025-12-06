<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_balance_histories', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('store_balance_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['income', 'withdrawal']);
            $table->uuid('reference_id');
            $table->string('reference_type');
            $table->decimal('amount', 26, 2);
            $table->string('remarks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_balance_histories');
    }
};
