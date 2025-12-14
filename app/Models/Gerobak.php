<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gerobak extends Model
{
    protected $guarded = ['id'];

    // Relasi ke pemilik
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke history (One to Many)
    public function histories()
    {
        return $this->hasMany(LocationHistory::class);
    }
}
