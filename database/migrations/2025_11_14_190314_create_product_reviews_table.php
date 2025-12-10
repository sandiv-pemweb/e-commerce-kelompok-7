<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->integer('rating');
            $table->text('review');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
