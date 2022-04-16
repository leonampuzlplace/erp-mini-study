<?php

namespace App\Http\Dto\Company;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class CompanyContactDto extends Data
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

    #[Rule('required|boolean')]
    public bool $is_default,

    #[Rule('nullable|string|max:60')]
    public ?string $contact_name,

    #[Rule('nullable|string|max:20')]
    public ?string $contact_ein,

    #[Rule('nullable|string|max:100')]
    public ?string $contact_type,

    #[Rule('nullable|string')]
    public ?string $contact_note,

    #[Rule('nullable|string|max:30')]
    public ?string $phone,

    #[Rule('nullable|string|email|max:100')]
    public ?string $email,
  ) {
  }
}
