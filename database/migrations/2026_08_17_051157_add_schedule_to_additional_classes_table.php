<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('additional_classes', function (Blueprint $table) {
            $table->string('day')->nullable()->after('price_usd'); // Misal: Day 1, Day 2
            $table->time('time')->nullable()->after('day');        // Misal: 09:00, 13:00
            $table->string('location')->nullable()->after('time'); // Misal: Hall A, Room 101
        });
    }

    public function down(): void
    {
        Schema::table('additional_classes', function (Blueprint $table) {
            $table->dropColumn(['day', 'time', 'location']);
        });
    }
};