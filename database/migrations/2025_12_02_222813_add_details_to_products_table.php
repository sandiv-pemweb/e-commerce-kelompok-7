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
        Schema::table('products', function (Blueprint $table) {
            $table->string('author')->nullable()->after('name');
            $table->string('publisher')->nullable()->after('author');
            $table->year('published_year')->nullable()->after('publisher');
            $table->decimal('discount_price', 26, 2)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['author', 'publisher', 'published_year', 'discount_price']);
        });
    }
};
