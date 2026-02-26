<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'name',
        'description',
        'daily_price',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function rentals()
    {
        return $this->hasMany('App\Models\Rental', 'equipment_id');
    }

    public function sports()
    {
        // IA: Je ne comprend pas trop comment faire cette relation entre equipment et sports, peux-tu m'aider et m'expliquer (avec photo du MRD)
        return $this->belongsToMany( 
            'App\Models\Sport',
            'equipment_sport',
            'equipment_id',
            'sport_id'
        );
    }
}
