<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
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
            'throttle:api',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'status' => config('appconfig.status.failed'),
                'code' => config('appconfig.code.bad_request'),
                'message' => 'Route not found, check and try again.',
            ], 404);
        });

        $exceptions->renderable(function (ValidationException $e) {
            $errors = $e->errors();
            $message = '';
            foreach ($errors as $key => $error) {
                $message = $error[0];
            }

            return response()->json([
                'status' => config('appconfig.status.failed'),
                'code' => config('appconfig.code.bad_request'),
                'message' => $message,
                'errors' => $errors,
            ], 422);
        });

        $exceptions->renderable(function (AuthenticationException $e) {
            return response()->json([
                'status' => config('appconfig.status.failed'),
                'code' => config('appconfig.code.bad_request'),
                'message' => 'Unauthorized',
                'errors' => [],
            ], 405);
        });

        $exceptions->renderable(function (AuthorizationException $e) {
            return response()->json([
                'status' => config('appconfig.status.failed'),
                'code' => config('appconfig.code.bad_request'),
                'message' => 'Unauthorized',
                'errors' => [],
            ], 405);
        });

    })->create();
