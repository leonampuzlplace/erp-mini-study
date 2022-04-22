<?php

namespace App\Http\Repositories;

use App\Exceptions\ModelNotFoundException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\Data;

abstract class BaseRepository
{
  private $isTransaction = false;
  protected $model;
  public array $page = [];
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
      $modelFound = $this->model->find($id);
      
      // Apagar registro permanentemente (delete trashed)
      if (!$modelFound) {
        $modelFound = $this->model
          ->whereId($id)
          ->onlyTrashed()
          ->first();

        throw_if(!$modelFound, new ModelNotFoundException(trans('message_lang.model_not_found') . ' Trashed id: ' . $id));
        return $modelFound->forceDelete();
      }

      // Apagar registro (alterar para trashed. Não exclui permanentemente)
      return $modelFound->delete();
    };

    // Controle de Transação
    return match ($this->isTransaction()) {
      true => DB::transaction(fn () => $executeDestroy($id)),
      false => $executeDestroy($id),
    };
  }

  /**
   * Obter registros a partir de page e Filter
   *
   * @param array $page
   * Configuração da paginação   
   * page = [
   *   'limit' => 10,       // Limite de registros por página
   *   'current' => 1,      // Página atual, setada!
   *   'paginate' => 0,     // Tipo de Paginação [0-paginate,1-simplePaginate,2-cursorPaginate,3-semPaginacao]
   *   'columns' => '*',    // Colunas retornadas no array. Ex: customer.id, customer.name, customer_address.address
   *   'cursor' => '',      // Paginação com cursor. Geralmente utilizado com scroll infinito no frontend
   *   'onlyData' => 0,     // [0-false,1-true] Não retorna informações da paginação. Retorna array somente com os dados.
   * ]   
   * 
   * @param array $filter
   * Configuração de filtro de dados
   * Exemplos em formato de queryParams
   * filter[where][tableName.fieldName][operator]
   * filter[orWhere][tableName.fieldName][operator]
   * filter[orderBy]
   * filter[onlyTrashed]   
   * 
   * @param array $filterEx
   * Filtro extra para utilizar da forma que desejar dentro da classe que extende BaseRepository
   * 
   * @return array
   */
  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    $this->page = $page ?? [];
    $this->filter = $filter ?? [];
    $this->filterEx = $filterEx ?? [];
    $queryBuilder = $this->indexBuilder();
    $resultInsideIndex = $this->indexInside($queryBuilder);

    // Paginação e Retorno dos dados
    return $this->indexGetAndPaginate($resultInsideIndex[0], $resultInsideIndex[1]);
  }

  /**
   * Inicialização do queryBuilder e filtro padrão aplicado
   *
   * @return Builder
   */
  public function indexBuilder(): Builder
  {
    $this->pageValidated();
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
   * Este método é chamado dentro do index. Utilizado para alterar queryBuilder e colunas a serem exibidas.
   * Retorna array com $queryBuilder e selectRaw de colunas a serem exibidas para o método Index()
   * FilterEx pode ser implementado no override desse metodo na classe que extende BaseRepository
   *
   * @param Builder $queryBuilder
   * @return array
   */
  public function indexInside(Builder $queryBuilder): array
  {
    return [$queryBuilder, $this->model->getTable() . '.*'];
  }

  /**
   * Paginação do index baseado em page e exibição das colunas
   * Retorna resultado em array
   *
   * @param Builder $queryBuilder
   * @param string $selectRaw
   * @return array
   * Retornar um array contendo queryBuilder e string de colunas a serem exibidas
   */
  public function indexGetAndPaginate(Builder $queryBuilder, String $selectRaw = '*'): array
  {
    // Campos a serem exibidos (Necessário se houver join)
    (($this->page['columns'][0] === '*') && (count($this->page['columns']) === 1))
      ? $queryBuilder->selectRaw($selectRaw)
      : $queryBuilder->selectRaw($this->page['columns']);

    // Paginação
    $queryBuilder = match ($this->page['paginate']) {
      0 => $queryBuilder->paginate($this->page['limit'], null, 'page', $this->page['current']),
      1 => $queryBuilder->simplePaginate($this->page['limit'], null, 'page', $this->page['current']),
      2 => $queryBuilder->cursorPaginate($this->page['limit'], null, 'cursor', $this->page['cursor']),
      default => $queryBuilder->get(), // Sem Paginação
    };

    // Resultado
    return (($this->page['onlyData'] == 1) && ($this->page['paginate'] <= 2))
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
    $modelFound = $this->model->find($id);
    throw_if(!$modelFound, new ModelNotFoundException(trans('message_lang.model_not_found') . ' id: ' . $id));
    return $modelFound->getData();
  }

  /**
   * Salvar registro e retornar DTO
   * 
   * Exemplo para salvar outros relacionamentos na classe que extende BaseRepository
   * $modelStored = $this->model->create($data);
   * $modelStored->tenantAddress()->createMany($data['tenant_address']);
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

    // Controle de Transação
    return match ($this->isTransaction()) {
      true => DB::transaction(fn () => $executeStore($data)),
      false => $executeStore($data),
    };
  }

  /**
   * Atualizar Registro e retorna DTO atualizado
   *
   * Exemplo para atualizar outros relacionamentos na classe que extende BaseRepository
   *   $modelFound = $this->model->findOrFail($id);
   *   tap($modelFound)->update($data);
   *   $modelFound->tenantAddress()->where('tenant_address.tenant_id', $id)->delete();
   *   $modelFound->tenantAddress()->createMany($data['tenant_address']);
   *   $modelFound->load('tenantAddress');
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

      // Atualizar
      tap($modelFound)->update($data);
      return $modelFound->getData();
    };

    // Controle de Transação
    return match ($this->isTransaction()) {
      true => DB::transaction(fn () => $executeUpdate($id, $data)),
      false => $executeUpdate($id, $data),
    };
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
   * Alterar flag de controle de transação dos dados
   *
   * @param boolean $active
   * @return self
   */
  public function setTransaction(bool $active): self
  {
    $this->isTransaction = $active;
    return $this;
  }

  /**
   * Obter valor do controle de transação dos dados
   *
   * @return boolean
   */
  public function isTransaction(): bool
  {
    return $this->isTransaction;
  }

  /**
   * Validar page
   * Se valores não preenchidos, será retornado um padrão
   *
   * @return void
   */
  public function pageValidated(): void
  {
    $this->page = [
      'limit' => $this->page['limit'] ?? 10,
      'current' => $this->page['current'] ?? 1,
      'paginate' => intval($this->page['paginate'] ?? 0),
      'columns' => $this->page['columns'] ?? ['*'],
      'cursor' => $this->page['cursor'] ?? null,
      'onlyData' => $this->page['onlyData'] ?? 0,
    ];
  }
}

