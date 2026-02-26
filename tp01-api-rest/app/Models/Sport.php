<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name'
    ];

    public function equipment()
    {
        return $this->belongsToMany(
            'App\Models\Equipment', // IA: Même chose que dans Equipment.php
            'equipmentsports',
            'sportId',
            'equipmentId'
        );
    }
}
