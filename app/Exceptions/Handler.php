<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthenticationException) {
            // If it's an API request, return JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                $authHeader = $request->header('Authorization');

                if (empty($authHeader)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token Required',
                    ], 401);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token Expired Or Invalid. Please Create a New Token.',
                    ], 401);
                }
            }

            // Otherwise, redirect frontend users to login
            return redirect()->guest(route('login'));
        }

        return parent::render($request, $exception);
    }
}
