<?php

namespace App\Http\Dto\Product;

use Illuminate\Validation\Rule as ValidationRule;
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

    #[Rule('nullable')]
    public object|array|null $unit,

    public ?int $category_id,

    #[Rule('nullable')]
    public object|array|null $category,

    #[Rule('nullable|integer')]
    public ?int $brand_id,

    #[Rule('nullable')]
    public object|array|null $brand,
  ) {
  }

  public static function rules(): array
  {
    return [
      'category_id' => [
        'nullable',
        'integer',
        ValidationRule::exists('category', 'id')->where(function ($query) {
          return $query->where('tenant_id', currentTenantId());
        }),
      ],
      'brand_id' => [
        'nullable',
        'integer',
        ValidationRule::exists('brand', 'id')->where(function ($query) {
          return $query->where('tenant_id', currentTenantId());
        }),
      ],
    ];
  }     
}
