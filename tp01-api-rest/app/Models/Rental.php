<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'start_date',
        'end_date',
        'total_price',
        'user_id',
        'equipment_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function equipment()
    {
        return $this->belongsTo('App\Models\Equipment', 'equipment_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'rental_id');
    }
}
