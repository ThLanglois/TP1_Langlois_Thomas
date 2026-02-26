<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone'
    ];

    public function rentals()
    {
        return $this->hasMany('App\Models\Rental', 'user_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'user_id');
    }
}
