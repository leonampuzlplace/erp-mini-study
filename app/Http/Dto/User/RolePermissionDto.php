<?php

namespace App\Http\Dto\User;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class RolePermissionDto extends Data
{
  public static function authorize(): bool
  {
    return true;
  }  

  public function __construct(
    #[Rule('nullable|integer')]
    public ?int $id,

    #[Rule('nullable|integer')]
    public ?int $role_id,

    #[Rule('required|string|max:255')]
    public string $action_name,

    #[Rule('required|string|max:255')]
    public string $action_group_description,

    #[Rule('required|string|max:255')]
    public string $action_name_description,

    #[Rule('nullable|boolean')]
    public bool $is_allowed,
  ) {
  }
}
