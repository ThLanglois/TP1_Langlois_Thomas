<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{   
    protected $fillable = [
        'name'
    ];

    public function equipment()
    {
        // IA: Même chose que dans Equipment.php
        return $this->belongsToMany(
            'App\Models\Equipment', 
            'equipment_sport',
            'sport_id',
            'equipment_id'
        );
    }
}
