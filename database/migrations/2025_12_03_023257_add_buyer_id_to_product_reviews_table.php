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
        Schema::table('product_reviews', function (Blueprint $table) {
            // Add buyer_id foreign key after transaction_id
            $table->foreignId('buyer_id')
                ->after('transaction_id')
                ->constrained('buyers')
                ->cascadeOnDelete();
            
            // Add composite index for better query performance
            $table->index(['buyer_id', 'product_id'], 'product_reviews_buyer_product_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            // Drop the index first
            $table->dropIndex('product_reviews_buyer_product_index');
            
            // Drop the foreign key constraint
            $table->dropForeign(['buyer_id']);
            
            // Drop the column
            $table->dropColumn('buyer_id');
        });
    }
};
