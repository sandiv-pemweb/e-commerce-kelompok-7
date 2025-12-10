<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {

            $table->foreignId('buyer_id')
                ->after('transaction_id')
                ->constrained('buyers')
                ->cascadeOnDelete();


            $table->index(['buyer_id', 'product_id'], 'product_reviews_buyer_product_index');
        });
    }


    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {

            $table->dropIndex('product_reviews_buyer_product_index');


            $table->dropForeign(['buyer_id']);


            $table->dropColumn('buyer_id');
        });
    }
};
