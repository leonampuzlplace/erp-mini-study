<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        JWTException::class,
        TokenBlacklistedException::class,
        TokenInvalidException::class,
        TokenExpiredException::class,
        ValidationException::class,
        CustomValidationException::class,
        ModelNotFoundException::class,
        NotFoundHttpException::class,
        RouteNotFoundException::class,
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
        $responseErrorExecuted = false;
        $exceptionName = (new \ReflectionClass($exception))->getShortName(); 

        if ($request->is("api/*")) {
            // Token não pode ser utilizado
            if ($exceptionName === 'TokenBlacklistedException') {
                responseError(
                    trans('auth_lang.token_cant_used'),
                    Response::HTTP_BAD_REQUEST,
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Token inválido
            if ($exceptionName === 'TokenInvalidException') {
                responseError(
                    trans('auth_lang.token_is_invalid'),
                    Response::HTTP_BAD_REQUEST,
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Token expirado
            if ($exceptionName === 'TokenExpiredException') {
                responseError(
                    trans('auth_lang.token_is_expired'),
                    Response::HTTP_BAD_REQUEST,
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Token não informado
            if ($exceptionName === 'JWTException') {
                responseError(
                    trans('auth_lang.token_not_provided'),
                    Response::HTTP_BAD_REQUEST,
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Validação dos dados
            if ($exceptionName === 'ValidationException') {
                responseError(
                    $exception->errors(),
                    $exception->status,
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Validação dos dados (Customizada)
            if ($exceptionName === 'CustomValidationException') {
                responseError(
                    $exception->errors(),
                    $exception->status(),
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Model não encontrado
            if ($exceptionName === 'ModelNotFoundException') {
                responseError(
                    $exception->getMessage(),
                    Response::HTTP_BAD_REQUEST,
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Model não encontrado
            if ($exceptionName === 'QueryException') {
                responseError(
                    $exception->getMessage(),
                    Response::HTTP_BAD_REQUEST,
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Rota não encontrada
            if ($exceptionName === 'NotFoundHttpException') {
                responseError(
                    trans('message_lang.not_found_route_http'),
                    Response::HTTP_NOT_FOUND,
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Rota não encontrada
            if ($exceptionName === 'RouteNotFoundException') {
                responseError(
                    trans('message_lang.route_not_found_or_token_invalid'),
                    Response::HTTP_NOT_FOUND,
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Caso nenhuma exceção seja executada acima.
            if (!$responseErrorExecuted) {
                responseError(
                    $exception->getMessage(),
                    Response::HTTP_BAD_REQUEST,
                    'Unexpected exception'
                );
            }
        }

        return parent::render($request, $exception);
    }

    public function responseErrorFromAuth(){}
}
