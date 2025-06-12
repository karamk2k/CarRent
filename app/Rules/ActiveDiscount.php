<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Discount;

class ActiveDiscount implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Discount::activeDiscount($value)->exists();
    }

    public function message(): string
    {
        return 'The selected discount is not currently active.';
    }
}

