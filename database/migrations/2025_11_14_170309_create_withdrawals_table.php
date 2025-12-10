<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('store_balance_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 26, 2);
            $table->string('bank_account_name');
            $table->string('bank_account_number');
            $table->string('bank_name');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
