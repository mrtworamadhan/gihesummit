<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_number')->unique();
            $table->enum('type', ['Single', 'Twin']);
            $table->integer('capacity')->default(1);
            $table->integer('booked_count')->default(0); // Untuk mengecek apakah Twin room sudah ada 1 orang
            $table->decimal('price_idr', 15, 2)->default(0);
            $table->decimal('price_usd', 15, 2)->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};