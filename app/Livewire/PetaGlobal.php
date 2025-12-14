<?php

namespace App\Livewire;

use App\Models\Gerobak;
use Livewire\Component;

class PetaGlobal extends Component
{
    public function render()
    {

        $gerobaks = Gerobak::where('is_active', true)->get();
        return view('livewire.peta-global', [
            'gerobaks' => $gerobaks
        ]);
    }
}
