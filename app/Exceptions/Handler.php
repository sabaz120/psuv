<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
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
        if ($exception instanceof AuthenticationException) {
            return redirect("/");
        }else
        if($exception instanceof AuthorizationException){
            $msg=$exception->getMessage() ?? "No estas autorizado para realizar esta petición.";
            $code=$exception->getCode() ?? 401;
            return response()->json([
            'message' => $msg
            ],$code);
        }elseif($exception instanceof RelationNotFoundException){
            return response()->json([
                'message' => 'No existe la relacion solicitada.'
                ],404);
        }elseif($exception instanceof ModelNotFoundException){
            return response()->json([
                'message' => 'No existe el recurso solicitado.'
                ],404);
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {

	// Here you can return your own response or work with request
	// return response()->json(['status' : false], 401);

	// This is the default
        return response()->json(['message' => $exception->getMessage()], 401);
    }
}
