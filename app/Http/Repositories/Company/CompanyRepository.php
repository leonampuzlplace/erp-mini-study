<?php

namespace App\Http\Repositories\Company;

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
      'company.*, ' .
        'company_address.is_default, ' .
        'company_address.zipcode, ' .
        'company_address.address, ' .
        'company_address.address_number, ' .
        'company_address.complement, ' .
        'company_address.district, ' .
        'company_address.city_id, ' .
        'company_address.reference_point, ' .
        'city.city_name, ' .
        'city.ibge_code, ' .
        'state.state_name, ' .
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

    throw_if(!$modelFound, new \Exception('No query results for $id = ' . $id));
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
    $dto->id = null;
    $data = $dto->toArray();
    $executeStore = function ($data) {
      $companyModel = $this->model->create($data);
      $companyModel->companyAddress()
        ->createMany($data['company_address']);

      return $this->show($companyModel->id);
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
    $dto->id = $id;
    $data = $dto->toArray();
    $executeUpdate = function ($id, $data) {
      $companyModel = $this->model->findOrFail($id);

      // Atualizar Company
      tap($companyModel)->update($data);

      // Atualizar CompanyAddress
      $companyModel->companyAddress()->where('company_address.company_id', $id)->delete();
      $companyModel->companyAddress()->createMany($data['company_address']);

      // Carregar relacionamentos
      $companyModel->load('companyAddress');
      
      return $companyModel->getData();
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
}
