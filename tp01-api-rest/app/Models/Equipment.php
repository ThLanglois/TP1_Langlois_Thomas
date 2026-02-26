<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'dailyPrice',
        'categoryId'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'categoryId');
    }

    public function rentals()
    {
        return $this->hasMany('App\Models\Rental', 'equipmentId');
    }

    public function sports()
    {
        return $this->belongsToMany( // IA: Je ne comprend pas trop comment faire cette relation entre equipment et sports, peux-tu m'aider et m'expliquer (avec photo du MRD)
            'App\Models\Sport',
            'equipmentsports',
            'equipmentId',
            'sportId'
        );
    }
}
