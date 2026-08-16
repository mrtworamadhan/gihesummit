<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: "Main Summit Certificate", "Workshop AI Certificate"
            $table->string('type')->default('main'); // 'main' untuk semua peserta, 'class' untuk kelas tertentu
            $table->unsignedBigInteger('additional_class_id')->nullable(); // Jika sertifikat ini khusus untuk kelas tertentu
            $table->string('template_path'); // Path gambar template kosong
            $table->boolean('is_published')->default(false); // Admin bisa menahan sertifikat sampai acara benar-benar selesai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};