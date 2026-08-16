<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            
            // Detail Tagihan
            $table->enum('registration_category', ['Domestic', 'International']);
            $table->enum('currency', ['IDR', 'USD']);
            $table->decimal('base_amount', 15, 2); // Harga dasar (misal: 3.000.000)
            $table->integer('unique_code')->nullable(); // 3 Angka unik (misal: 123)
            $table->decimal('final_amount', 15, 2)->nullable(); // Total bayar (misal: 3.000.123)
            
            // Bukti & Status
            $table->string('proof_of_transfer_path')->nullable(); // Bisa null sebelum di-upload
            $table->boolean('needs_invoice')->default(false);
            $table->enum('payment_status', ['unpaid', 'pending_verification', 'paid'])->default('unpaid');
            
            // Tracking Verifikasi Admin
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};