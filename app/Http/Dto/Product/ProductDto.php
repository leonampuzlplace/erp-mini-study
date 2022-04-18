<?php

namespace App\Http\Dto\Product;

use PhpParser\Node\Expr\Cast\Double;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class ProductDto extends Data
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

    #[Rule('required|string|max:120')]
    public string $name,

    #[Rule('nullable|string|max:36')]
    public ?string $reference_code,

    #[Rule('nullable|string|max:36')]
    public ?string $ean_code,

    #[Rule('nullable|numeric|min:0')]
    public ?float $cost_price,

    #[Rule('nullable|numeric|min:0')]
    public ?float $sale_price,

    #[Rule('nullable|numeric|min:0')]
    public ?float $minimum_quantity,

    #[Rule('nullable|numeric|min:0')]
    public ?float $current_quantity,

    #[Rule('nullable|boolean')]
    public ?bool $move_stock,

    #[Rule('nullable|string')]
    public ?string $note,

    #[Rule('nullable|boolean')]
    public ?bool $discontinued,

    #[Rule('required|integer|exists:unit,id')]
    public int $unit_id,

    #[Rule('nullable|integer')] // Verificar existencia no tenant TODO
    public ?int $category_id,

    #[Rule('nullable|integer')] // Verificar existencia no tenant TODO
    public ?int $brand_id,
  ) {
  }
}
