<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
class NotInFavorites implements Rule
{
   
    public function passes($attribute, $value): bool
{
    return ! Auth::user()
        ->favorites()
        ->where('car_id', $value)
        ->whereHas('car', fn ($q) => $q->whereNull('deleted_at'))
        ->exists();
}

public function message(): string
{
    return 'This car is already in your favorites.';
}

}
