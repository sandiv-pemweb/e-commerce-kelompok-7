<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_status ENUM('unpaid', 'paid', 'refunded', 'waiting') DEFAULT 'unpaid'");
    }


    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN payment_status ENUM('unpaid', 'paid', 'refunded') DEFAULT 'unpaid'");
    }
};
