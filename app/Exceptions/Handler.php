<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\QueryException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle database integrity constraint violations
        $this->renderable(function (QueryException $e) {
            // Check for integrity constraint violation
            if (strpos($e->getMessage(), 'Integrity constraint violation') !== false || 
                strpos($e->getMessage(), 'FOREIGN KEY') !== false ||
                $e->errorInfo[0] == '23000') {
                return response()->view('errors.constraint-violation', ['exception' => $e], 422);
            }
        });
    }
}
