<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        // Check if the request is an API request
        $isApiRequest = $request->expectsJson() || $request->is('api/*');

        if ($isApiRequest) {
            // AuthenticationException
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->getMessage(),
                    'errors' => ['error' => [$exception->getMessage()]]
                ], 401);
            }

            // AuthorizationException
            if ($exception instanceof AuthorizationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this area of the application.',
                    'errors' => ['error' => [$exception->getMessage()]]
                ], 403);
            }
        }

        // Check if the exception is an instance of ValidationException
        if ($exception instanceof ValidationException) {
            if ($isApiRequest) {
                return response()->json([
                    'success' => false,
                    'message' => $exception->validator->errors()->first(),
                    'errors' => $exception->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                return redirect()->back()->withInput($request->input())->withErrors($exception->errors());
            }
        }

        // Handle other types of exceptions and default error rendering
        if ($isApiRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'errors' => ['error' => [$exception->getMessage()]]
            ], $this->isHttpException($exception) ? $exception->getStatusCode() : Response::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            if ($exception instanceof ModelNotFoundException) {
                return redirect()->back()->with('error', 'Model not found.');
            } 
            return parent::render($request, $exception);
        }
    }
}
