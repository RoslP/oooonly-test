<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function comfortCategories()
    {
        return $this->belongsToMany(
            ComfortCategory::class,
            'position_comfort_category',
            'position_id',
            'max_comfort_category_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
