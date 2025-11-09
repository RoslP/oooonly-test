<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarBinding extends Model
{
    use HasFactory;

    protected $table = 'cars_binding';

    protected $fillable = [
        'car_id',
        'user_id',
        'start_time',
        'end_time',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
