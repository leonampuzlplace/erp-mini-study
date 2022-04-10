<?php

namespace App\Http\Services\Company;

use App\Exceptions\CustomValidationException;
use App\Http\Dto\Company\CompanyDto;
use App\Http\Repositories\Company\CompanyRepository;
use Spatie\LaravelData\Data;

class CompanyService
{
  public function __construct(
    protected CompanyRepository $companyRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(CompanyRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->companyRepository->destroy($id);
  }

  public function index(array $page = [], array $filter = [], array $filterEx = []): array
  {
    return $this->companyRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): CompanyDto
  {
    return $this->companyRepository->show($id);
  }

  public function store(CompanyDto $dto): CompanyDto
  {
    $this->beforeSave($dto, 0);
    return $this->companyRepository->setWithTransaction(true)->store($dto);
  }

  public function update(int $id, CompanyDto $dto): CompanyDto
  {
    $this->beforeSave($dto, 1);
    return $this->companyRepository->update($id, $dto);
  }

  /**
   * Executar método antes de salvar registro
   *
   * @param Data $dto
   * @param integer $store0_update1
   * @return void
   */
  protected function beforeSave(Data $dto, int $store0_update1): void
  {
    // Disparar exceção se houver erros
    $errors = $this->validateData($dto, $store0_update1);
    throw_if((count($errors) > 0), new CustomValidationException($errors));

    // Formatar dados antes de salvar
    $this->formatData($dto, $store0_update1);
  }

  /**
   * Validar dados
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
   * Formatar dados
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
