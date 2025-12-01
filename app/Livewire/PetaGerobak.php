<?php

namespace App\Livewire;
use Livewire\Attributes\On;
use Livewire\Component;

class PetaGerobak extends Component
{
    // public function render()
    // {
    //     return view('livewire.peta-gerobak');
    // }

    public $lat = 0;
    public $lng = 0;

    // Mendengarkan Event dari Reverb
    // Format: echo:{nama-channel},{NamaEvent}
    #[On('echo:track-channel,LokasiUpdate')] 
    public function updateKoordinat($event)
    {
        // Update variable public
        $this->lat = $event['lat'];
        $this->lng = $event['lng'];
    }

    public function render()
    {
        return view('livewire.peta-gerobak');
    }

}
