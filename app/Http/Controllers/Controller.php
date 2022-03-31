<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

use function PHPSTORM_META\map;

// HTTP_OK = (200) Requisição foi bem sucedida com retorno no corpo da mensagem.
// HTTP_CREATED = (201) Requisição foi bem sucedida e um novo recurso foi criado e retornado no corpo da mensagem.
// HTTP_NO_CONTENT = (204) Requisição foi bem sucedida e não tem corpo de mensagem.
// HTTP_BAD_REQUEST = (400) Servidor não pode processar a requisição devido a alguma falha por parte do servidor. Ex: erro de sintaxe.
// HTTP_NOT_FOUND = (404) Servidor não encontrou o recurso solicitado.

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $baseResponse = [
        'code' => '',
        'error' => false,
        'message' => '',
        'result' => [],
    ];

    public function responseSuccess(array $data, int $code = Response::HTTP_OK, string $msg = ''): JsonResponse
    {
        $this->baseResponse['code'] = $code;
        $this->baseResponse['error'] = false;
        $this->baseResponse['message'] = $msg;
        $this->baseResponse['result'] = $data;

        return response()
            ->json($this->baseResponse, $code)
            ->send();
    }

    public function responseError(string $msg = '', int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $this->baseResponse['code'] = $code;
        $this->baseResponse['error'] = true;
        $this->baseResponse['message'] = $msg;
        $this->baseResponse['result'] = [];

        return response()
            ->json($this->baseResponse, $code)
            ->send();
    }

    public function queryParamsValidated(Request $request): array
    {
        $pageOptions = $request->only(['perPage', 'page', 'paginateType', 'cursor', 'onlyData']);
        $pageOptions['columns'] = explode(',', $request->query('columns', '*'));

        $filter = $request->query('filter');
        $filter['orderBy'] = explode(',', $request->query('filter', [])['orderBy'] ?? '');
        $filter['customSearchValue'] = explode(',', $request->query('filter', [])['customSearchValue'] ?? '');

        return [
            'pageOptions' => $pageOptions, 
            'filter' => $filter
        ];
    }
}
