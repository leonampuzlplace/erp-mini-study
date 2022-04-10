<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            // Validação dos dados
            if ($exception instanceof ValidationException) {
                responseError(
                    $exception->errors(),
                    $exception->status,
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Validação dos dados (Customizada)
            if ($exception instanceof CustomValidationException) {
                responseError(
                    $exception->errors(),
                    $exception->status(),
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Model não encontrado
            if ($exception instanceof ModelNotFoundException) {
                responseError(
                    $exception->errors(),
                    $exception->status(),
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Model não encontrado
            if ($exception instanceof QueryException) {
                responseError(
                    $exception->getMessage(),
                    Response::HTTP_BAD_REQUEST,
                    $exceptionName,
                );
                $responseErrorExecuted = true;
            }

            // Rota não encontrada
            if ($exception instanceof NotFoundHttpException) {
                responseError(
                    'Server could not find the route requested.',
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
}
