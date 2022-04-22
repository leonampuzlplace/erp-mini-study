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
    protected RoleRepository $repository
  ) {
  }

  public static function make(): Self
  {
    return new self(RoleRepository::make());
  }

  public function destroy(int $id): bool
  {
    return $this->repository->destroy($id);
  }

  public function index(array|null $page = [], array|null $filter = [], array|null $filterEx = []): array
  {
    return $this->repository->index($page, $filter, $filterEx);
  }

  public function show(int $id): RoleDto
  {
    return $this->repository->show($id);
  }

  public function store(RoleDto $dto): RoleDto
  {
    return $this->repository->setTransaction(true)->store($dto);
  }

  public function update(int $id, RoleDto $dto): RoleDto
  {
    return $this->repository->setTransaction(true)->update($id, $dto);
  }

  public function permissionTemplate(): array
  {
    return [
      ...BrandService::permissionTemplate(),
      ...CategoryService::permissionTemplate(),
      ...PersonService::permissionTemplate(),
      ...StockService::permissionTemplate(),
    ];    
  }

  /**
   * Template Padrão para facilitar cadastro de Permissões
   *
   * @param string $routeName
   * Utilize o mesmo nome da rota antes do ".".
   * Supondo que temos as seguintes rotas: 
   * Route::get("/person", "personController@index")->name("person.store");
   * Route::get("/person", "personController@update")->name("person.update");
   * Route::get("/person", "personController@destroy")->name("person.destroy");
   * $routeName deve ser: person 
   * O que está após o ponto se for .store, .update, .destroy, será adiconado como default
   * 
   * @param string $actionGroupDescription
   * Descrição do grupo de ações
   * Exemplo: Pessoas
   * 
   * @return array
   */
  public static function permissionTemplateDefault(string $routeName, string $actionGroupDescription): array 
  {
    $permissionTemplate = [
      [
        'action_name' => "${routeName}.formAccess",
        'action_group_description' => $actionGroupDescription,
        'action_name_description' => 'Acesso ao formulário',
        'is_allowed' => false
      ],
      [
        'action_name' => "${routeName}.store",
        'action_group_description' => $actionGroupDescription,
        'action_name_description' => 'Incluir',
        'is_allowed' => false
      ],
      [
        'action_name' => "${routeName}.update",
        'action_group_description' => $actionGroupDescription,
        'action_name_description' => 'Editar',
        'is_allowed' => false
      ],
      [
        'action_name' => "${routeName}.destroy",
        'action_group_description' => $actionGroupDescription,
        'action_name_description' => 'Deletar',
        'is_allowed' => false
      ],
    ];
    return $permissionTemplate;
  }
}
