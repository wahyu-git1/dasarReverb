<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Gerobak;
use App\Events\LokasiGerobakUpdated;

// Ini Route khusus testing API (Akses via: http://127.0.0.1:8000/api/tembak-lokasi)
Route::post('/tembak-lokasi', function (Request $request) {
    
    // Hardcode ID 1 untuk testing
    $gerobak = Gerobak::find(1); 

    if (!$gerobak) {
        return response()->json(['message' => 'Gerobak ID 1 hilang!'], 404);
    }
    
    // Update Lat/Lng
    $gerobak->update([
        'lat' => $request->lat,
        'lng' => $request->lng,
        'is_active' => true
    ]);

    // Fire Event ke Reverb
    LokasiGerobakUpdated::dispatch($gerobak);

    return response()->json([
        'status' => 'Sukses!',
        'posisi_baru' => [$gerobak->lat, $gerobak->lng]
    ]);
});