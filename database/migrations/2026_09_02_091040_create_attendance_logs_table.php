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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agenda_id')->constrained('agendas')->cascadeOnDelete();
            $table->foreignId('gatekeeper_id')->nullable()->constrained('gatekeepers')->nullOnDelete();
            
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete(); 
            
            $table->enum('status', ['success', 'rejected', 'force_accepted']);
            $table->string('notes')->nullable(); // Alasan ditolak atau catatan force accept
            
            $table->timestamps(); // created_at akan menjadi waktu scan (scanned_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
