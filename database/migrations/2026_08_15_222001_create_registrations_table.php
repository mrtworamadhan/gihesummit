<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete(); 
            
            // Partisipasi
            $table->string('role_at_summit')->nullable();
            $table->string('showcase_category')->nullable();
            $table->string('willingness_to_cosign_declaration')->nullable();
            
            // Logistik & Akomodasi
            $table->string('departure_city_country')->nullable();
            $table->dateTime('estimated_arrival')->nullable();
            $table->dateTime('estimated_departure')->nullable();
            $table->string('extend_options')->nullable();
            $table->boolean('needs_accommodation_assist')->default(true);
            $table->boolean('requires_visa_letter')->nullable();
            $table->string('dietary_restrictions')->nullable();
            $table->string('accessibility_needs')->nullable();
            $table->boolean('tour_guide_needed')->default(false); // Khusus WNA
            
            // Validasi & Konfirmasi
            $table->string('mandate_letter_path')->nullable(); 
            $table->boolean('consent_data_use')->default(true);
            $table->boolean('is_requested_confirmation')->default(false); // Trigger WA Admin
            
            $table->timestamps();
        });

        // Tabel Pivot untuk Relasi Many-to-Many Registrasi dan Kelas Tambahan
        Schema::create('additional_class_registration', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('additional_class_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('additional_class_registration');
        Schema::dropIfExists('registrations');
    }
};