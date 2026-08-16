<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('additional_classes', function (Blueprint $table) {
            
            $table->decimal('price_idr', 15, 2)->default(0);
            $table->decimal('price_usd', 15, 2)->default(0);
            
        });
    }

    public function down(): void
    {
        Schema::table('additional_classes', function (Blueprint $table) {
            $table->dropColumn(['price_idr', 'price_usd']);
        });
    }
};