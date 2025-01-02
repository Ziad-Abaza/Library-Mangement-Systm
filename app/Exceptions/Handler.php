<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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

    /**
     * Customize the response for exceptions.
     */
    public function render($request, Throwable $exception)
    {
        // Handle the 404 not found exception for API routes
        if ($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Resource Not Found',
                'message' => 'The resource you are looking for could not be found. Please check the URL or resource identifier.',
                'status_code' => 404
            ], 404);
        }

        // Handle AuthorizationException (403 - Forbidden)
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'You do not have the necessary permissions to access this resource.',
                'status_code' => 403
            ], 403);
        }

        // Handle other types of exceptions with a more detailed message
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'error' => 'Validation Error',
                'message' => 'The data provided is invalid. Please review the input fields and try again.',
                'errors' => $exception->errors(),
                'status_code' => 422
            ], 422);
        }

        // Handle general errors (for example, server errors)
        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => 'An unexpected error occurred on the server. Please try again later.',
                'status_code' => $exception->getStatusCode()
            ], $exception->getStatusCode());
        }

        // Default exception rendering
        return parent::render($request, $exception);
    }
}
