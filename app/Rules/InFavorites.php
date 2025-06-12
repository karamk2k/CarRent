<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class InFavorites implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Auth::user()
            ->favorites()
            ->where('car_id', $value)
            ->whereHas('car', fn ($q) => $q->whereNull('deleted_at'))
            ->exists();
    }

    public function message(): string
    {
        return 'This car is not in your favorites.';
    }
}

