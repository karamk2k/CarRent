<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class EmailAlreadyVerifiedException extends Exception
{
    /**
     * Report the exception.
     */
    public function report(): void
    {
        //
    }

    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request):RedirectResponse|Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'You have already verified your email.'], 403);
        }

        return redirect()->route('home')->with('message', 'You have already verified your email..');
    }

}
