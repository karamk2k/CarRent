<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'model',
        'color',
        'year',
        'price',
        'image',
        'category',
        'transmissions',
        'seats',
        'fuel_type',
        'fuel_capacity',
        'available_at',
    ];

    protected $casts = [
        'available_at' => 'datetime',
    ];

public function categoryRes()
{
    return $this->belongsTo(Category::class, 'category');
}

    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
    public function userfavortie()
    {
        return $this->hasMany(UserFavorite::class);
    }

    public function carAvailable(): bool
    {
        if (is_null($this->available_at)) {
            return true;
        }
        return Carbon::parse($this->available_at)->isPast();
    }
    

}
