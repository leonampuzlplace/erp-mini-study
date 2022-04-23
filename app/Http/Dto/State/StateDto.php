<?php

namespace App\Http\Dto\State;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class StateDto extends Data
{
  public static function authorize(): bool
  {
    return true;
  }  

  public function __construct(
    #[Rule('nullable|integer')]
    public ?int $id,

    #[Rule('required|string|max:50')]
    public string $name,

    #[Rule('required|string|max:2')]
    public string $abbreviation,
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
