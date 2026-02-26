<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'phone'
    ];

    public function rentals()
    {
        return $this->hasMany('App\Models\Rental');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }
}
