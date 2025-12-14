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
        Schema::create('location_histories', function (Blueprint $table) {
            $table->id();
            
            // Terhubung ke gerobak mana history ini milik
            $table->foreignId('gerobak_id')->constrained('gerobaks')->onDelete('cascade');
            
            // Data Koordinat
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            
            // Kita butuh created_at untuk tahu KAPAN dia ada di titik ini
            // (Penting untuk LSTM time-series)
            $table->timestamps(); 
            
            // Indexing biar query history nanti cepat
            $table->index(['gerobak_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_histories');
    }
};
