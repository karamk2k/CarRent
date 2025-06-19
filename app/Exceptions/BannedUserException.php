<?php

namespace App\Exceptions;

use Exception;

class BannedUserException extends Exception
{
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Your account has been banned.'], 403);
        }

        return response()->view('banned');
    }
}
