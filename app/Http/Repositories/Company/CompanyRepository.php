<?php

namespace App\Http\Repositories\Company;

use App\Exceptions\CustomValidationException;
use App\Exceptions\ModelNotFoundException;
use App\Http\Repositories\BaseRepository;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\Data;

class CompanyRepository extends BaseRepository
{
  private function __construct(Company $company)
  {
    parent::__construct($company);
  }

  public static function make(): Self
  {
    return new self(new Company);
  }

  /**
   * Método executado dentro de BaseRepository.index()
   * Adicionar join de tabelas e mostrar colunas específicas
   *
   * @param Builder $queryBuilder
   * @return array
   */
  public function indexInside(Builder $queryBuilder): array
  {
    return [
      $queryBuilder
        ->leftJoin('company_address', 'company_address.company_id', 'company.id')
        ->leftJoin('city', 'city.id', 'company_address.city_id')
        ->leftJoin('state', 'state.id', 'city.state_id')
        ->where('company_address.is_default', '1'),
        'company.*, '.
        'company_address.is_default, '.
        'company_address.zipcode, '.
        'company_address.address, '.
        'company_address.address_number, '.
        'company_address.complement, '.
        'company_address.district, '.
        'company_address.city_id, '.
        'company_address.reference_point, '.
        'city.city_name, '.
        'city.ibge_code, '.
        'state.state_name, '.
        'state.state_abbreviation'
    ];
  }

  /**
   * Localizar um único registro por ID
   * Acrescenta with para mostrar relacionamentos
   *
   * @param integer $id
   * @return Data
   */
  public function show(int $id): Data
  {
    $modelFound = $this->model
      ->where('id', $id)
      ->with('companyAddress.city.state')
      ->first();

    throw_if(!$modelFound, new ModelNotFoundException('No query results for $id = ' . $id));
    return $modelFound->getData();
  }

  /**
   * Salvar registro e retornar DTO
   * Acrescenta createMany para salvar relacionamentos
   * 
   * @param Data $dto
   * @return Data
   */
  public function store(Data $dto): Data
  {
    $this->beforeSave($dto, 0);
    $dto->id = null;
    $data = $dto->toArray();
    $executeStore = function ($data) {
      $modelFound = $this->model->create($data);
      $modelFound->companyAddress()
        ->createMany($data['company_address']);

      return $this->show($modelFound->id);
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
   * @param integer $id
   * @param Data $dto
   * @return Data
   */
  public function update(int $id, Data $dto): Data
  {
    $this->beforeSave($dto, 1);
    $dto->id = $id;
    $data = $dto->toArray();
    $executeUpdate = function ($id, $data) {
      $modelFound = $this->model->findOrFail($id);

      // Atualizar Company
      tap($modelFound)->update($data);

      // Atualizar CompanyAddress
      $modelFound->companyAddress()->where('company_address.company_id', $id)->delete();
      $modelFound->companyAddress()->createMany($data['company_address']);

      // Carregar relacionamentos
      $modelFound->load('companyAddress');
      
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
   * Executar método antes de salvar registro
   *
   * @param Data $dto
   * @param integer $store0_update1
   * @return void
   */
  public function beforeSave(Data $dto, int $store0_update1): void
  {
    // Disparar exceção se houver erros
    $errors = $this->validateData($dto, $store0_update1);
    throw_if((count($errors) > 0), new CustomValidationException($errors));

    // Formatar dados antes de salvar
    $this->formatData($dto, $store0_update1);
  }

  /**
   * Validar dados se necessário
   *
   * @param Data $dto
   * @param integer $store0_update1
   * @return array
   */
  public function validateData(Data $dto, int $store0_update1): array
  {
    $errors = [];

    // Endereço deve conter um único registro como padrão. is_default = 1
    $filtered = array_filter(
      $dto->company_address->toArray(),
      function ($item) {
        return $item['is_default'] == 1;
      }
    );
    if (count($filtered) !== 1) {
      $errors['company_address'] = 'The company address must have a single record with field is_default = 1.';
    }

    return $errors;
  }

  /**
   * Formatar dados se necessário
   *
   * @param Data $dto
   * @param integer $store0_update1
   * @return void
   */
  public function formatData(Data $dto, int $store0_update1): void
  {
    $dto->company_ein = formatCpfCnpj($dto->company_ein);
  }
}
