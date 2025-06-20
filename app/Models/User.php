<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable

{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'profile_picture',
        'password',
        'phone_number',
        'address',
        'date_of_birth',
        'gender',
        'profile_image',
        'role_id',
        'otp',
        'otp_expires_at',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }
    public function favorites()
    {
        return $this->hasMany(UserFavorite::class);
    }

    public function hasPendingRental(): bool
{
    return $this->rentals()
        ->where('status', 'pending')
        ->exists();
}

public function hasOngoingRental(): bool
{
    return $this->rentals()
        ->where('status','confirmed')
        ->where('end_date', '>', now())
        ->exists();
}

/**
 * Get all bans for the user
 */
public function bans()
{
    return $this->hasMany(UserBan::class);
}

/**
 * Get the user's active ban
 */
public function activeBan()
{
    return $this->hasOne(UserBan::class)->active();
}

/**
 * Check if the user is currently banned
 */
public function isBanned(): bool
{
    return $this->activeBan()->exists();
}

/**
 * Get bans issued by this user (for admins)
 */
public function issuedBans()
{
    return $this->hasMany(UserBan::class, 'banned_by');
}

}
