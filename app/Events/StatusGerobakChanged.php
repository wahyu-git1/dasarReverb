<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StatusGerobakChanged implements ShouldBroadcast
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
            new Channel('peta-global'),        
        ];
    }

    public function broadcastAs(): string
    {
        return 'status.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->gerobak->id,
            'is_active' => $this->gerobak->is_active,
            // Jika aktif kirim koordinat, jika tutup kirim null biar marker hilang
            'lat' => $this->gerobak->is_active ? $this->gerobak->lat : null,
            'lng' => $this->gerobak->is_active ? $this->gerobak->lng : null,
        ];
    }


}
