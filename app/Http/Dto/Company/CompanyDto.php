<?php

namespace App\Http\Dto\Company;

use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Validation\Validator;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class CompanyDto extends Data
{
  public static function authorize(): bool
  {
    return true;
  }  

  public function __construct(
    #[Rule('nullable|integer')]
    public ?int $id,
    
    #[Rule('required|string|max:80')]
    public string $business_name = '',
    
    #[Rule('required|string|max:80')]
    public string $alias_name = '',

    public string $company_ein = '',

    #[Rule('nullable|string|max:20')]
    public string $state_registration = '',

    #[Rule('nullable|integer|in:0,1')]
    public int $icms_taxpayer = 0,
    
    #[Rule('nullable|string|max:20')]
    public string $municipal_registration = '',

    #[Rule('nullable|string')]
    public string $note_general = '',
    
    #[Rule('nullable|string|max:255')]
    public string $internet_page = '',

    #[Rule('nullable|string|min:10')]
    public ?string $created_at,

    #[Rule('nullable|string|min:10')]
    public ?string $updated_at,
    
    /** @var CompanyAddressDto[] */
    public ?DataCollection $company_address_dto,
  ) {
  }

  public static function rules(): array
  {
    return [
      'company_ein' => [
        'required', 
        'string',
        'max:20',
        ValidationRule::unique('company', 'company_ein')->ignore(intVal(request()->route('company'))),
      ],
    ];
  }  

  public static function withValidator(Validator $validator): void
  {
    // Validar CPF ou CNPJ
    $validator->after(function ($validator) {
      $company_ein = request()->get('company_ein');
      if (!cpfOrCnpjIsValid($company_ein)) {
        $validator->errors()->add('company_ein', 'Document ('. $company_ein .') is not valid!');
      }      
    });
  }
  
  // public static function messages(): array
  // {
  //   return [
  //     'business_name.required' => 'A business_name is required',
  //     'alias_name.required' => 'An alias_name is required',
  //   ];
  // }
}
