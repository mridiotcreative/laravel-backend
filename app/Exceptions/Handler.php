<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Lang;
use Throwable;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use HttpResponseTraits;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Not Found Response
        if ($exception instanceof NotFoundHttpException) {
            if ($request->expectsJson()) {
                return
                    $this->failure(Lang::get('messages.url_not_found'), Response::HTTP_NOT_FOUND);
            }
            return response()->view('errors.404');
        }
        // Api Auth Response
        if ($request->expectsJson() && $exception instanceof AuthenticationException) {
            return $this->failure(Lang::get('messages.unauthenticated'), Response::HTTP_UNAUTHORIZED);
        }
        return parent::render($request, $exception);
    }
}
