
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">🗺️ Peta Pantauan Mitra Real-time</h1>

    <div id="map" style="height: 600px; width: 100%; border-radius: 10px; border: 2px solid #333;"></div>

    <div id="log-activity" class="mt-2 text-sm text-gray-600">
        Menunggu sinyal...
    </div>
</div>


<script type="module">
document.addEventListener('livewire:initialized', () => {
        
        // 1. Inisialisasi Peta (Koordinat awal Surabaya)
        var map = L.map('map').setView([-7.2575, 112.7521], 13);

        // Pasang Tile Layer (Peta Jalanan OpenStreetMap)
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // ----------------------------------------------------
        // 2. RENDER DATA AWAL (Dari Database)
        // ----------------------------------------------------
        
        // Kita butuh object untuk menyimpan referensi marker berdasarkan ID Gerobak
        // Format: { 1: MarkerObject, 2: MarkerObject, ... }
        const markers = {}; 

        // Data awal dari PHP Blade dikonversi ke JSON JS
        const initialData = @json($gerobaks);

        initialData.forEach(gerobak => {
            if(gerobak.lat && gerobak.lng) {
                // Buat Marker
                const marker = L.marker([gerobak.lat, gerobak.lng])
                    .addTo(map)
                    .bindPopup(`<b>${gerobak.nama_gerobak}</b><br>${gerobak.deskripsi}`);
                
                // Simpan marker ke object 'markers' dengan key ID gerobak
                markers[gerobak.id] = marker;
            }
        });

        // ----------------------------------------------------
        // 3. LISTEN REVERB (Real-time Update)
        // ----------------------------------------------------
        
        // Channel: 'peta-global' (Sesuai Event broadcastOn)
        // Event: '.lokasi.updated' (Sesuai Event broadcastAs, pakai TITIK di depan)
        
        window.Echo.channel('peta-global')
            .listen('.lokasi.updated', (e) => {  // <--- Menangkap Si
                console.log("Update Lokasi:", e);
                document.getElementById('log-activity').innerText = `Update masuk: ${e.nama} (${e.lat}, ${e.lng})`;
                // e = Data JSON dari Reverb (lat, lng, nama)
                // Cek apakah marker untuk gerobak ini sudah ada?
                if (markers[e.id]) {
                    // Update posisi marker yang sudah ada (Animasi geser)
                    markers[e.id].setLatLng([e.lat, e.lng]);
                    markers[e.id].setPopupContent(`<b>${e.nama}</b><br>Baru update!`);
                } else {
                    // Jika belum ada (misal baru buka toko), buat marker baru
                    const newMarker = L.marker([e.lat, e.lng])
                        .addTo(map)
                        .bindPopup(`<b>${e.nama}</b><br>Baru bergabung`);
                    
                    markers[e.id] = newMarker;
                }
            });

            // Tambahan: Listen status toko tutup (Opsional)
            window.Echo.channel('peta-global')
            .listen('.status.changed', (e) => {
                 if (!e.is_active && markers[e.id]) {
                     // Kalau tutup, hapus marker dari peta
                     map.removeLayer(markers[e.id]);
                     delete markers[e.id];
                 }
            });
    });


</script>





