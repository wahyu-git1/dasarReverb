<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Gerobak;
use App\Models\LocationHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun ADMIN
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@app.com',
            'password' => Hash::make('password'), // password: password
            'role' => 'admin',
        ]);

        // 2. Buat Akun USER Biasa (Pembeli)
        User::create([
            'name' => 'Budi Pembeli',
            'email' => 'user@app.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 3. Buat 5 Akun MITRA beserta GEROBAK-nya
        // Kita set lokasi awal di sekitar Surabaya (-7.2575, 112.7521)
        $startLat = -7.2575;
        $startLng = 112.7521;

        for ($i = 1; $i <= 5; $i++) {
            // A. Buat User Mitra
            $mitra = User::create([
                'name' => "Mitra Gerobak $i",
                'email' => "mitra$i@app.com", // mitra1@app.com, mitra2@app.com ...
                'password' => Hash::make('password'),
                'role' => 'mitra',
            ]);

            // Hitung koordinat acak di sekitar lokasi pusat (biar gak numpuk)
            // Geser sedikit sekitar 100-500 meter
            $lat = $startLat + (rand(-20, 20) / 10000); 
            $lng = $startLng + (rand(-20, 20) / 10000);

            // B. Buat Data Gerobak untuk Mitra ini
            $gerobak = Gerobak::create([
                'user_id' => $mitra->id,
                'nama_gerobak' => "Nasi Goreng $i",
                'deskripsi' => 'Spesial pedas gila level 10',
                'is_active' => true, // Anggap sedang jualan
                'lat' => $lat,
                'lng' => $lng,
            ]);

            // C. Buat Sedikit History Pergerakan (Untuk bekal Data Science nanti)
            // Kita buat 3 titik history mundur ke belakang
            for ($j = 0; $j < 3; $j++) {
                LocationHistory::create([
                    'gerobak_id' => $gerobak->id,
                    'lat' => $lat - ($j * 0.0001), // Simulasi posisi sebelumnya
                    'lng' => $lng - ($j * 0.0001),
                    'created_at' => now()->subMinutes($j * 10), // 10 menit yang lalu
                ]);
            }
        }

        echo "✅ Data Dummy Berhasil Dibuat!\n";
        echo "   - Admin: admin@app.com\n";
        echo "   - Mitra: mitra1@app.com s/d mitra5@app.com\n";
        echo "   - Password semua akun: password\n";
    }
}