<?php

namespace App\Events;

use App\Models\Gerobak;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LokasiGerobakUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Gerobak $gerobak)

    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
                new Channel('peta-global'),  // <--- Alamat Tujuan (Channel)      
            ];
    }

    public function broadcastAs(): string
    {
        return 'lokasi.updated';        // Nama Event (Label)
    }

    // Data ringkas untuk dikirim ke JS (Hemat Bandwidth)
    public function broadcastWith(): array
    {
        return [
            'id' => $this->gerobak->id,
            'nama' => $this->gerobak->nama_gerobak,
            'lat' => $this->gerobak->lat,
            'lng' => $this->gerobak->lng,
            'icon' => 'gerobak-icon.png' // Bisa custom icon
        ];
    }


}
