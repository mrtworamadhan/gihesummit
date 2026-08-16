<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('day'); // 1 untuk Day 1, 2 untuk Day 2
            $table->string('time_range'); // contoh: "08.00 - 09.00"
            $table->string('session_name'); // contoh: "Keynote I"
            $table->text('topic_description')->nullable(); // Penjelasan topik
            $table->string('speaker')->nullable(); // Nama pembicara
            $table->boolean('is_break')->default(false); // Penanda khusus untuk waktu istirahat (Coffee/Lunch break)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};