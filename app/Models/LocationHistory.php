<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationHistory extends Model
{
    protected $guarded = ['id'];
    // Opsional: Matikan updated_at karena history sifatnya "catat sekali, jangan ubah"
    // public $timestamps = true; (biarkan true agar created_at terisi otomatis)

}
