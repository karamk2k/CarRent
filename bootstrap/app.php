<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\isUserBanned;
use App\Http\Middleware\EnsureEmailIsNotVerified;

use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => IsAdmin::class,
            'isUserBanned' => isUserBanned::class,
            'ensureEmailIsNotVerified' => EnsureEmailIsNotVerified::class,
        ]);
    })
     ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, $request) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect()->guest(route('login'));
        });


        $exceptions->render(function (ValidationException $e, $request) {
            return $request->expectsJson()
                ? response()->json([
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], 422)
                : redirect()->back()
                    ->withErrors($e->errors())
                    ->withInput();
        });


        $exceptions->render(function (ModelNotFoundException $e, $request) {
            throw new NotFoundHttpException('Resource not found.', $e);
        });
    })->create();
