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
    (
        \#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3}) |
        rgb\(\s*(\d{1,3}\s*,\s*){2}\d{1,3}\s*\) |
        rgba\(\s*(\d{1,3}\s*,\s*){3}(0|1|0?\.\d+)\s*\) |
        (aliceblue|antiquewhite|aqua|aquamarine|azure|beige|bisque|black|blanchedalmond|blue|blueviolet|brown|
        burlywood|cadetblue|chartreuse|chocolate|coral|cornflowerblue|cornsilk|crimson|cyan|darkblue|darkcyan|
        darkgoldenrod|darkgray|darkgreen|darkgrey|darkkhaki|darkmagenta|darkolivegreen|darkorange|darkorchid|
        darkred|darksalmon|darkseagreen|darkslateblue|darkslategray|darkslategrey|darkturquoise|darkviolet|
        deeppink|deepskyblue|dimgray|dimgrey|dodgerblue|firebrick|floralwhite|forestgreen|fuchsia|gainsboro|
        ghostwhite|gold|goldenrod|gray|green|greenyellow|grey|honeydew|hotpink|indianred|indigo|ivory|khaki|
        lavender|lavenderblush|lawngreen|lemonchiffon|lightblue|lightcoral|lightcyan|lightgoldenrodyellow|
        lightgray|lightgreen|lightgrey|lightpink|lightsalmon|lightseagreen|lightskyblue|lightslategray|
        lightslategrey|lightsteelblue|lightyellow|lime|limegreen|linen|magenta|maroon|mediumaquamarine|
        mediumblue|mediumorchid|mediumpurple|mediumseagreen|mediumslateblue|mediumspringgreen|mediumturquoise|
        mediumvioletred|midnightblue|mintcream|mistyrose|moccasin|navajowhite|navy|oldlace|olive|olivedrab|
        orange|orangered|orchid|palegoldenrod|palegreen|paleturquoise|palevioletred|papayawhip|peachpuff|peru|
        pink|plum|powderblue|purple|rebeccapurple|red|rosybrown|royalblue|saddlebrown|salmon|sandybrown|
        seagreen|seashell|sienna|silver|skyblue|slateblue|slategray|slategrey|snow|springgreen|steelblue|tan|
        teal|thistle|tomato|turquoise|violet|wheat|white|whitesmoke|yellow|yellowgreen)
    )
$/ix';

        return preg_match($pattern, $value) === 1;
    }

    public function message()
    {
        return 'The :attribute must be a valid color (hex, rgb, rgba, or named).';
    }
}
