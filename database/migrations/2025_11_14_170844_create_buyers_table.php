<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Termwind\Components\Ul;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('buyers', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('profile_picture')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
