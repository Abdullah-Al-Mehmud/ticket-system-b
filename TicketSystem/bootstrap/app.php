<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // Make sure this is imported
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth' => Illuminate\Auth\Middleware\Authenticate::class,
            'role' => Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Corrected argument order: $e (Exception) first, $request (Request) second
        $exceptions->render(function (AuthenticationException $e, Request $request) {

            if ($e->getMessage() === 'Unauthenticated.') {
                return response()->json([
                    'status' => 'false',
                    'message' => 'You are not Authorize'
                ], 404);
            }

            // For web requests, still redirect to login
            return response()->json(['message' => $e->getMessage()], 500);
        });

        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            return response()->json([
                'status' => false,
                "message" => "You does not have the right permissions.",
            ], 403); // 403 for forbidden
        });
    })->create();
