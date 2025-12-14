<?php

namespace App\Http\Controllers\Mitra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\LokasiGerobakUpdated;
use App\Events\StatusGerobakChanged;

class GerobakController extends Controller
{
    //
    public function updateLokasi(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);
        
        // Ambil gerobak milik user yang sedang login
        $gerobak = auth()->user()->gerobak;

        if (!$gerobak) {
            return response()->json(['message' => 'Anda belum punya gerobak'], 404);
        }

        // A. Update Posisi Terkini di Tabel Gerobak
        $gerobak->update([
            'lat' => $request->lat,
            'lng' => $request->lng,
            'is_active' => true // Asumsikan kalau kirim lokasi berarti sedang aktif
        ]);

        // B. Simpan Jejak ke History (PENTING UNTUK DATA SCIENCE NANTI)
        // Kita simpan jejak pergerakan untuk bahan K-Means / LSTM
        $gerobak->histories()->create([
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);

        // C. Teriak ke Reverb (Masuk Queue Redis)
        LokasiGerobakUpdated::dispatch($gerobak);

        return response()->json(['status' => 'success']);

    }

    // 2. API untuk Buka/Tutup Toko
    public function toggleStatus(Request $request)

    {
        $request->validate(['is_active' => 'required|boolean']);

        $gerobak = auth()->user()->gerobak;

        if (!$gerobak) {
            return response()->json(['message' => 'Gerobak not found'], 404);
        }


        $gerobak->update(['is_active' => $request->is_active]);

        // Broadcast status baru (agar marker muncul/hilang di peta Admin)
        StatusGerobakChanged::dispatch($gerobak);

        return response()->json([
            'status' => 'success', 
            'message' => $request->is_active ? 'Toko Buka' : 'Toko Tutup'
        ]);
    }
}
