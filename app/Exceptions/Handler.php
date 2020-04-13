<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [LimitExceededException::class];

    protected $dontFlash = ['password', 'password_confirmation'];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof LimitExceededException) {
            return new Response(
                ["message" => "Betting provider API limit exceeded"],
                Response::HTTP_FAILED_DEPENDENCY,
            );
        }

        return parent::render($request, $exception);
    }
}
