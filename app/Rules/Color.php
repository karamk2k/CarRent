<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Color implements Rule
{
    public function passes($attribute, $value)
    {
        // This regex validates:
        // - HEX colors (#fff, #ffffff)
        // - rgb(), rgba() colors
        // - basic named colors (red, blue, etc.)
        $pattern = '/^
            (#
                ([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})
            )
            |(rgb\(\s*(\d{1,3}\s*,\s*){2}\d{1,3}\s*\))
            |(rgba\(\s*(\d{1,3}\s*,\s*){3}(0|1|0?\.\d+)\s*\))
            |(red|blue|green|black|white|yellow|orange|purple|gray)
            $/ix';

        return preg_match($pattern, $value) === 1;
    }

    public function message()
    {
        return 'The :attribute must be a valid color (hex, rgb, rgba, or named).';
    }
}
