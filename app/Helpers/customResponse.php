<?php
// Como configurar arquivo helper? 
// Adicione o caminho em composer.json na seção autoload, files. 
// Depois rodar comando composer dump-autoload -o

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

function baseResponse(): array
{
  return [
    'code' => '',
    'error' => false,
    'message' => '',
    'result' => [],
  ];
}

function responseSuccess(mixed $result = [], int $code = Response::HTTP_OK, string $msg = ''): JsonResponse
{
    // Quando nenhuma mensagem informado, seta um default
    if (!$msg){
        $msg = match ($code) {
            Response::HTTP_OK => trans('message_lang.http_ok'),
            Response::HTTP_CREATED => trans('message_lang.http_created'),
            Response::HTTP_BAD_REQUEST => trans('message_lang.http_bad_request'),
            Response::HTTP_NOT_FOUND => trans('message_lang.http_not_found'),
            default => '',
        };
    }

    // Configurar Resposta
    $baseResponse = baseResponse();
    $baseResponse['code'] = $code;
    $baseResponse['error'] = false;
    $baseResponse['message'] = $msg;
    $baseResponse['result'] = $result;

    // Retornar Resposta
    return response()
        ->json($baseResponse, $code)
        ->send();
}

function responseError(mixed $result = [], int $code = Response::HTTP_BAD_REQUEST, string $msg = ''): JsonResponse
{
    // Configurar resposta
    $baseResponse = baseResponse();
    $baseResponse['code'] = $code;
    $baseResponse['error'] = true;
    $baseResponse['message'] = $msg;
    $baseResponse['result'] = $result;

    // Retornar Resposta
    return response()
        ->json($baseResponse, $code)
        ->send();
}
