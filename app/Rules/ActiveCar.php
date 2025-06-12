<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Rule;

use App\Models\Car;

class ActiveCar implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
       $car=Car::find($value);
       return $car->carAvailable();
    }
      public function message(): string
    {
        return 'The selected car is not currently active.';
    }
}
