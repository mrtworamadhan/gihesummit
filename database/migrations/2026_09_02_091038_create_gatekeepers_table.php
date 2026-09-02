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
        Schema::create('gatekeepers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama petugas, misal: Budi
            $table->string('magic_token')->unique(); // Token unik untuk URL scanner
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gatekeepers');
    }
};
