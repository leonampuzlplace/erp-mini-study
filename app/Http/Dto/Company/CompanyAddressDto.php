<?php

namespace App\Http\Dto\Company;

use App\Http\Dto\City\CityDto;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class CompanyAddressDto extends Data
{
  public static function authorize(): bool
  {
    return true;
  }  

  public function __construct(
    #[Rule('nullable|integer')]
    public ?int $id,

    #[Rule('nullable|integer')]
    public ?int $company_id,

    #[Rule('nullable|integer|in:0,1')]
    public ?int $is_default,

    #[Rule('nullable|string|max:10')]
    public ?string $zipcode,

    #[Rule('required|string|max:100')]
    public string $address,

    #[Rule('nullable|string|max:15')]
    public ?string $address_number,

    #[Rule('nullable|string|max:100')]
    public ?string $complement,

    #[Rule('nullable|string|max:100')]
    public ?string $district,

    #[Rule('nullable|string|max:100')]
    public ?string $reference_point,

    #[Rule('required|integer')]
    public int $city_id,

    #[Rule('nullable')]
    public object|array|null $city,
  ) {
  }
}
