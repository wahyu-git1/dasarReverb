<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gerobak;
use App\Events\LokasiGerobakUpdated;
use Illuminate\Support\Facades\Log;

class SimulasiGerobakJalan extends Command
{
    // Nama perintah terminal nanti
    protected $signature = 'gerobak:jalan {--loop=10 : Jumlah langkah pergerakan}';
    
    protected $description = 'Simulasi pergerakan gerobak secara otomatis';

    public function handle()
    {
        $loop = $this->option('loop');
        $this->info("🚀 Memulai simulasi pergerakan untuk $loop langkah...");

        $gerobaks = Gerobak::where('is_active', true)->get();

        if ($gerobaks->isEmpty()) {
            $this->error("❌ Tidak ada gerobak aktif! Pastikan seeder sudah dijalankan.");
            return;
        }

        for ($i = 1; $i <= $loop; $i++) {
            $this->line("\n⏱️  Langkah ke-$i");

            foreach ($gerobaks as $gerobak) {
                // 1. Logic Pergerakan Acak (Jalan-jalan random)
                // 0.0001 derajat lat/lng itu kira-kira bergerak 10 meter
                $moveLat = rand(-20, 20) * 0.0001; 
                $moveLng = rand(-20, 20) * 0.0001;

                $newLat = $gerobak->lat + $moveLat;
                $newLng = $gerobak->lng + $moveLng;

                // 2. Update Database (Posisi Terkini)
                $gerobak->update([
                    'lat' => $newLat,
                    'lng' => $newLng
                ]);

                // 3. Simpan History (Untuk Data Science nanti)
                // $gerobak->histories()->create([...]); // (Opsional, aktifkan jika mau menuhin history)

                // 4. 🔥 FIRE EVENT REVERB 🔥
                // Ini yang bikin browser Admin update sendiri
                LokasiGerobakUpdated::dispatch($gerobak);

                $this->comment("   📍 {$gerobak->nama_gerobak} pindah ke [$newLat, $newLng]");
            }

            // Tunggu 2 detik sebelum langkah berikutnya
            sleep(2);
        }

        $this->info("\n✅ Simulasi selesai!");
    }
}