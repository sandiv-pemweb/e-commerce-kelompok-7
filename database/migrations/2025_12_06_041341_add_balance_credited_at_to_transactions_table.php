<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->timestamp('balance_credited_at')->nullable()->after('order_status');
        });

    }


    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('balance_credited_at');
        });

    }
};
