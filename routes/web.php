<?php

use App\Events\LokasiUpdate;
use Illuminate\Support\Facades\Route;
use App\Livewire\PetaGerobak;
use App\Http\Controllers\Mitra\GerobakController;
use App\Livewire\PetaGlobal;




Route::get('/', PetaGerobak::class);

// Route::get('/', function () {
//     return view('welcome');
// });

// simulasi menembak data 

Route::get('/kirim-lokasi/{lat}/{lng}', function ($lat, $lng) {

       // trigger event
    LokasiUpdate::dispatch((float)$lat, (float)$lng);
    return "Lokasi terkirim: Lat=$lat, Lng=$lng";
});

// Perutean (Routing) Event: Saat Reverb menerima data atau event (misalnya, melalui koneksi WebSocket), fungsi dispatch bertanggung jawab untuk menentukan komponen atau fungsi mana yang harus memproses event tersebut berdasarkan kriteria tertentu, seperti jenis event, saluran (channel), atau payload data [1].
// Pemanggilan Handler: Setelah rute yang tepat diidentifikasi, dispatch memanggil (trigger) fungsi atau metode handler terkait yang berisi logika aplikasi spesifik untuk menangani event tersebut [1].
// Manajemen Status dan Efek Samping: Dalam konteks arsitektur modern (sering kali terinspirasi oleh Elm Architecture atau Redux), fungsi dispatch sering kali menjadi satu-satunya cara untuk menginstruksikan sistem agar melakukan perubahan status (state) atau memulai efek samping (side effects), seperti panggilan API [1].
// Secara singkat, dispatch bertindak sebagai pusat kendali yang memastikan setiap masukan (input) dalam aplikasi real-time Anda mengarah pada tindakan atau pembaruan yang benar. 

Route::middleware(['auth', 'mitra'])->group(function () {
    
    // Endpoint Update Lokasi
    Route::post('/mitra/update-lokasi', [GerobakController::class, 'updateLokasi'])
        ->name('mitra.update-lokasi');

    // Endpoint Buka/Tutup Toko
    Route::post('/mitra/toggle-status', [GerobakController::class, 'toggleStatus'])
        ->name('mitra.toggle-status');
});

Route::get('/dashboard-peta', PetaGlobal::class)->name('dashboard.peta');