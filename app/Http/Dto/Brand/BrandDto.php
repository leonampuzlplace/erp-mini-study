<?php

namespace App\Http\Dto\Brand;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class BrandDto extends Data
{
  public static function authorize(): bool
  {
    return true;
  }  

  public function __construct(
    #[Rule('nullable|integer')]
    public ?int $id,

    #[Rule('nullable|integer')]
    public ?int $tenant_id,

    #[Rule('required|string|max:60')]
    public string $name,

    #[Rule('nullable|string|min:10')]
    public ?string $created_at,

    #[Rule('nullable|string|min:10')]
    public ?string $updated_at,
  ) {
  }

  /**
   * Utilizado para formatar os dados caso seja necessário
   *
   * @return array
   */
  public function toResource(): array
  {
    return parent::toArray();
  }
}
