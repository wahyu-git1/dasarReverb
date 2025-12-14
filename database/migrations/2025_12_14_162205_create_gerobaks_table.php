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
        Schema::create('gerobaks', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (Hanya user dengan role 'mitra' yang punya ini)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('nama_gerobak');
            $table->text('deskripsi')->nullable(); // Misal: "Jualan Nasi Goreng Spesial"
        
            // Status apakah sedang jualan atau tutup
            $table->boolean('is_active')->default(false);

            // Posisi Terakhir (Real-time update masuk kesini)
        // Gunakan decimal(10, 8) untuk presisi GPS yang akurat
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gerobaks');
    }
};
