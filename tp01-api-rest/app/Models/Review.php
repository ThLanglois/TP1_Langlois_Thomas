<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'rating',
        'comment',
        'userId',
        'rentalId'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'userId');
    }

    public function rental()
    {
        return $this->belongsTo('App\Models\Rental', 'rentalId');
    }
}
