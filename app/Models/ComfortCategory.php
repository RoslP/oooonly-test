<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComfortCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function positions()
    {
        return $this->belongsToMany(
            Position::class,
            'position_comfort_category',
            'max_comfort_category_id',
            'position_id'
        );
    }

    public function carModels()
    {
        return $this->hasMany(CarModel::class);
    }
}
