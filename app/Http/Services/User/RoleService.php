<?php

namespace App\Http\Services\User;

use App\Http\Dto\User\RoleDto;
use App\Http\Repositories\User\RoleRepository;
use App\Http\Services\Brand\BrandService;
use App\Http\Services\Category\CategoryService;
use App\Http\Services\Person\PersonService;
use App\Http\Services\Stock\StockService;

class RoleService
{
  public function __construct(
    protected RoleRepository $roleRepository
  ) {
  }

  public static function make(): Self
  {
    return new self(RoleRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->roleRepository->destroy($id);
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->roleRepository->index($page, $filter, $filterEx);
  }

  public function show(int $id): RoleDto
  {
    return $this->roleRepository->show($id);
  }

  public function store(RoleDto $dto): RoleDto
  {
    return $this->roleRepository->setTransaction(true)->store($dto);
  }

  public function update(int $id, RoleDto $dto): RoleDto
  {
    return $this->roleRepository->setTransaction(true)->update($id, $dto);
  }

  public function permissionTemplate(): array
  {
    return [
      ...BrandService::make()->permissionTemplate(),
      ...CategoryService::make()->permissionTemplate(),
      ...PersonService::make()->permissionTemplate(),
      ...StockService::make()->permissionTemplate(),
    ];    
  }

  /**
   * Template Padrão para facilitar cadastro de Permissões
   *
   * @param string $actionName
   * Utilize o mesmo nome da rota antes do ".".
   * Supondo que temos as seguintes rotas: 
   * Route::get("/person", "personController@index")->name("person.store");
   * Route::get("/person", "personController@update")->name("person.update");
   * Route::get("/person", "personController@destroy")->name("person.destroy");
   * $actionName deve ser: person 
   * O que está após o ponto se for .store, .update, .destroy, será adiconado como default
   * 
   * @param string $actionGroupDescription
   * Descrição do grupo de ações
   * Exemplo: Pessoas
   * 
   * @return array
   */
  public static function permissionTemplateDefault(string $actionName, string $actionGroupDescription): array 
  {
    $permissionTemplate = [
      [
        'action_name' => "${actionName}.formAccess",
        'action_group_description' => $actionGroupDescription,
        'action_name_description' => 'Acesso ao formulário',
        'is_allowed' => false
      ],
      [
        'action_name' => "${actionName}.store",
        'action_group_description' => $actionGroupDescription,
        'action_name_description' => 'Incluir',
        'is_allowed' => false
      ],
      [
        'action_name' => "${actionName}.update",
        'action_group_description' => $actionGroupDescription,
        'action_name_description' => 'Editar',
        'is_allowed' => false
      ],
      [
        'action_name' => "${actionName}.destroy",
        'action_group_description' => $actionGroupDescription,
        'action_name_description' => 'Deletar',
        'is_allowed' => false
      ],
    ];
    return $permissionTemplate;
  }
}
