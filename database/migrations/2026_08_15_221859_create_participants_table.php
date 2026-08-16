<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Detail Institusi & Delegasi (Melengkapi data di tabel users)
            $table->string('type_of_institution')->nullable();
            $table->text('institution_address')->nullable();
            $table->string('province')->nullable();
            $table->string('website_social_media')->nullable();
            $table->string('institution_scale')->nullable();
            $table->string('position_title')->nullable();
            
            // UUID untuk Barcode / QR Code
            $table->uuid('uuid_barcode')->unique();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};