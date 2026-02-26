<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'startDate',
        'endDate',
        'totalPrice',
        'userId',
        'equipmentId'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'userId');
    }

    public function equipment()
    {
        return $this->belongsTo('App\Models\Equipment', 'equipmentId');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'rentalId');
    }
}
