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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Serah Terima Kunci Kamar", "Gala Dinner"
            $table->enum('type', ['general', 'check_in', 'class']); // Tipe agenda untuk penentu rule validasi
            $table->foreignId('additional_class_id')->nullable()->constrained('additional_classes')->nullOnDelete(); // Jika agenda ini khusus kelas tertentu
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
