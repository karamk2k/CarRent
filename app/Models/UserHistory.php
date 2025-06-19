<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_history';

    protected $fillable = [
        'user_id',
        'car_id',
        'rent_date',
        'rental_id',
        'return_date',
        'total_price',
        'status',
        'payment_method',
        'payment_status'
    ];

    protected $casts = [
        'rent_date' => 'date',
        'return_date' => 'date',
        'total_price' => 'decimal:2'
    ];

    /**
     * Get the user that owns the history.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the car associated with the history.
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}
