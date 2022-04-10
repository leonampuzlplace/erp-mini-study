<?php

namespace App\Http\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\Data;

abstract class BaseRepository
{
  private $withTransaction = false;
  protected $model;
  public array $pageOption = [];
  public array $filter = [];
  public array $filterEx = [];

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  /**
   * Apagar registro
   * Se executado duas vezes, registro será apagado permanentemente
   *
   * @param integer $id
   * @return boolean
   */
  public function destroy(int $id): bool
  {
    $executeDestroy = function ($id) {
      $modelFound = $this->model
        ->where('id', $id)
        ->first();
      
      // Apagar registro permanentemente (delete trashed)
      if (!$modelFound) {
        $modelFound = $this->model
          ->where('id', $id)
          ->onlyTrashed()
          ->first();

        throw_if(!$modelFound, new \Exception('No query results for trashed $id = ' . $id));
        return $modelFound->forceDelete();
      }
      throw_if(!$modelFound, new \Exception('No query results for $id = ' . $id));

      // Apagar registro (alterar para trashed. Não exclui permanentemente)
      return $modelFound->delete();
    };

    return match ($this->getWithTransaction()) {
      true => DB::transaction(
        function () use ($executeDestroy, $id) {
          return $executeDestroy($id);
        }
      ),
      false => $executeDestroy($id),
    };
  }

  /**
   * Obter registros a partir de pageOption e Filter
   *
   * @param array $pageOption
   * Configuração da paginação   
   * pageOption = [
   *   'perPage' => 10,     // Quantidade de Registros por página
   *   'page' => 1,         // Página Setada
   *   'paginateType' => 0, // Tipo de Paginação [0-paginate,1-simplePaginate,2-cursorPaginate,3-semPaginacao]
   *   'columns' => '*',    // Colunas retornadas no array. Ex: customer.id, customer.name, customer_address.address
   *   'cursor' => '',      // Paginação com cursor. Geralmente utilizado com scroll infinito no frontend
   *   'onlyData' => 0,     // [0-false,1-true] Não retorna informações da paginação. Retorna array somente com os dados.
   * ]   
   * 
   * @param array $filter
   * Configuração de filtro de dados
   * filter = [ 
   *   where = [                 // [where, orWhere]
   *     tableName.FieldName = [ // [tableName.FieldName] Exemplo: company.alias_name
   *       equal = [             // [equal, greater, less, greaterOrEqual, lessOrEqual, different, likeInitial, likeFinal, likeAnyWhere, likeEqual]
   *         value               // [] Conteúdo da busca
   *       ]
   *     ],
   *     [], [], ...
   *   ],
   *   orderBy = 'tableName.FieldName', // Ordenar campos. Exemplo: company.id desc ou company.id, company.alias_name desc
   *   onlyTrashed = 1                  // [0,1] Registros apagados
   * ],
   * 
   * Exemplos de queryParams
   *  filter[orWhere][company.business_name][likeAnyWhere]=Leonam
   *  filter[orWhere][company.alias_name][likeAnyWhere]=Leonam
   *  filter[where][company.id][equal]=1
   *  filter[where][company.created_at][greaterOrEqual]=2022-04-07 00:00:00
   *  filter[where][company.created_at][lessOrEqual]=2022-04-07 00:00:00
   *  filter[orderBy]=company.id desc
   *  filter[onlyTrashed]=1
   * 
   * @param array $filterEx
   * Filtro extra para utilizar da forma que desejar dentro da classe que extende BaseRepository
   * 
   * @return array
   */
  public function index(array $pageOption = [], array $filter = [], array $filterEx = []): array
  {
    $this->pageOption = $pageOption;
    $this->filter = $filter;
    $this->filterEx = $filterEx;
    $queryBuilder = $this->indexBuilder();
    $resultInsideIndex = $this->indexInside($queryBuilder);

    // Paginação e Retorno dos dados
    return $this->indexGetAndPaginate($resultInsideIndex[0], $resultInsideIndex[1]);
  }

  /**
   * Este método é chamado dentro do index. Utilizado para alterar queryBuilder e colunas a serem exibidas.
   * Retorna array com $queryBuilder e selectRaw de colunas a serem exibidas para o método Index()
   * FilterEx pode ser implementado no override desse metodo na classe que extende BaseRepository
   *
   * @param Builder $queryBuilder
   * @return array
   */
  public function indexInside(Builder $queryBuilder): array
  {
    return [$queryBuilder, $this->model->getTable().'.*'];
  }

  /**
   * Inicialização do queryBuilder e filtro padrão aplicado
   *
   * @return Builder
   */
  public function indexBuilder(): Builder
  {
    $this->pageOptionValidated();
    $queryBuilder = $this->model->query();
    
    // Condição where e orWhere
    foreach (['where', 'orWhere'] as $whereOrWhere) {
      $queryBuilder->where(function ($query) use ($whereOrWhere) {
        foreach ($this->filter[$whereOrWhere] ?? [] as $key => $value) {
          $fieldName = $key; // Nome da Coluna
          
          foreach ($value as $key => $value) {
            $operator = $key; // Operador (Ex: =,>,<,>=,<=,like,etc)
          
            $fieldValues = explode(',', $value); // Conteúdo do filtro
            if ((count($fieldValues) >= 1) && (trim($fieldValues[0]) !== '')) {
              foreach ($fieldValues as $value) {
                $fieldValue = trim($value);
                if ($fieldValue) {
                  $formated = $this->formatOperatorAndFieldValue($operator, $fieldValue);
                  ($whereOrWhere === 'where')
                    ? $query->where($fieldName, $formated[0], $formated[1])
                    : $query->orWhere($fieldName, $formated[0], $formated[1]);
                }
              }
            }
          }
        }
      });
    }

    // Ordenação
    $orderByColumns = explode(',', $this->filter['orderBy'] ?? '');
    if ((count($orderByColumns) >= 1) && (trim($orderByColumns[0]) !== '')) {
      foreach ($orderByColumns as $value) {
        if (str_contains($value, ' desc')) {
          $orderByColumn = trim(str_replace(' desc', '', $value));
          $orderByDirection = 'desc';
        } else {
          $orderByColumn = trim(str_replace(' asc', '', $value));
          $orderByDirection = 'asc';
        }
        $queryBuilder->orderBy($orderByColumn, $orderByDirection);
      }
    }

    // Registros Apagados
    if (intVal($this->filter['onlyTrashed'] ?? 0) === 1) {
      $queryBuilder->onlyTrashed();
    }

    return $queryBuilder;
  }

  /**
   * Formatar Operador e Valor do Campo de Pesquisa/filtro
   * Retorna array com sinal do operador e campo de pesquisa
   *
   * @param string $operator
   * @param string $fieldValue
   * @return array
   */
  private function formatOperatorAndFieldValue(string $operator, string $fieldValue): array
  {
    return match (strtolower($operator)) {
      'equal' => ['=', $fieldValue],
      'greater' => ['>', $fieldValue],
      'less' => ['<', $fieldValue],
      'greaterorequal' => ['>=', $fieldValue],
      'lessorequal' => ['<=', $fieldValue],
      'different' => ['<>', $fieldValue],
      'likeinitial' => ['like', $fieldValue . '%'],
      'likefinal' => ['like', '%' . $fieldValue],
      'likeanywhere' => ['like', '%' . $fieldValue . '%'],
      'likeequal' => ['like', $fieldValue],
    };
  }

  /**
   * Paginação do index baseado em pageOption e exibição das colunas
   * Retorna resultado em array
   *
   * @param Builder $queryBuilder
   * @param string $selectRaw
   * @return array
   */
  public function indexGetAndPaginate(Builder $queryBuilder, String $selectRaw = '*'): array
  {
    // Campos a serem exibidos (Necessário se houver join)
    (($this->pageOption['columns'][0] === '*') && (count($this->pageOption['columns']) === 1))
      ? $queryBuilder->selectRaw($selectRaw)
      : $queryBuilder->selectRaw($this->pageOption['columns']);

    // Paginação
    $queryBuilder = match ($this->pageOption['paginateType']) {
      0 => $queryBuilder->paginate($this->pageOption['perPage'], null, 'page', $this->pageOption['page']),
      1 => $queryBuilder->simplePaginate($this->pageOption['perPage'], null, 'page', $this->pageOption['page']),
      2 => $queryBuilder->cursorPaginate($this->pageOption['perPage'], null, 'cursor', $this->pageOption['cursor']),
      default => $queryBuilder->get(), // Sem Paginação
    };

    // Resultado
    return (($this->pageOption['onlyData'] == 1) && ($this->pageOption['paginateType'] <= 2))
      ? $queryBuilder->toArray()['data']
      : $queryBuilder->toArray();
  }

  /**
   * Localizar um único registro por ID
   *
   * @param integer $id
   * @return Data
   */
  public function show(int $id): Data
  {
    $modelFound = $this->model
      ->where('id', $id)
      ->first();
    
    throw_if(!$modelFound, new \Exception('No query results for $id = ' . $id));
    return $modelFound->getData();
  }

  /**
   * Salvar registro e retornar DTO
   * 
   * Exemplo para salvar outros relacionamentos na classe que extende BaseRepository
   * $modelStored = $this->model->create($data);
   * $modelStored->companyAddress()->createMany($data['company_address']);
   * return $this->show($modelStored->id);
   *
   * @param Data $dto
   * @return Data
   */
  public function store(Data $dto): Data
  {
    $dto->id = null;
    $data = $dto->toArray();
    $executeStore = function ($data) {
      $modelStored = $this->model->create($data);
      return $this->show($modelStored->id);
    };

    return match ($this->getWithTransaction()) {
      true => DB::transaction(
        function () use ($executeStore, $data) {
          return $executeStore($data);
        }
      ),
      false => $executeStore($data),
    };
  }

  /**
   * Atualizar Registro e retorna DTO atualizado
   *
   * Exemplo para atualizar outros relacionamentos na classe que extende BaseRepository
   *   $modelFound = $this->model->findOrFail($id);
   *   tap($modelFound)->update($data);
   *   $modelFound->companyAddress()->where('company_address.company_id', $id)->delete();
   *   $modelFound->companyAddress()->createMany($data['company_address']);
   *   $modelFound->load('companyAddress');
   *   return $modelFound->getData();
   * 
   * @param integer $id
   * @param Data $dto
   * @return Data
   */
  public function update(int $id, Data $dto): Data
  {
    $dto->id = $id;
    $data = $dto->toArray();
    $executeUpdate = function ($id, $data) {
      $modelFound = $this->model->findOrFail($id);
      return $modelFound->getData();
    };

    return match ($this->getWithTransaction()) {
      true => DB::transaction(
        function () use ($executeUpdate, $id, $data) {
          return $executeUpdate($id, $data);
        }
      ),
      false => $executeUpdate($id, $data),
    };
  }    


  /**
   * Alterar flag de controle de transação dos dados
   *
   * @param boolean $active
   * @return self
   */
  public function setWithTransaction(bool $active): self
  {
    $this->withTransaction = $active;
    return $this;
  }

  /**
   * Obter valor do controle de transação dos dados
   *
   * @return boolean
   */
  public function getWithTransaction(): bool
  {
    return $this->withTransaction;
  }

  /**
   * Validar pageOption
   * Se valores não preenchidos, será retornado um padrão
   *
   * @return void
   */
  public function pageOptionValidated(): void
  {
    $this->pageOption = [
      'perPage' => $this->pageOption['perPage'] ?? 10,
      'page' => $this->pageOption['page'] ?? 1,
      'paginateType' => intval($this->pageOption['paginateType'] ?? 0),
      'columns' => $this->pageOption['columns'] ?? ['*'],
      'cursor' => $this->pageOption['cursor'] ?? null,
      'onlyData' => $this->pageOption['onlyData'] ?? 0,
    ];
  }
}

