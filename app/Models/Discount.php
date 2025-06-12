<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'percentage',
        'start_date',
        'end_date',
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
    public function scopeActiveDiscount($query, $name)
    {
        return $query->where('name', $name)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }
}
