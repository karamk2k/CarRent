<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserFavorite extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_favorites';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'car_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

        public function car()
    {
        return $this->belongsTo(\App\Models\Car::class, 'car_id', 'id');
    }
}   
