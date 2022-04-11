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
    public string $alias_name,

    #[Rule('required|string|max:80')]
    public string $business_name,

    public string $company_ein,

    #[Rule('nullable|string|max:20')]
    public ?string $state_registration,

    #[Rule('nullable|integer|in:0,1')]
    public ?int $icms_taxpayer,
    
    #[Rule('nullable|string|max:20')]
    public ?string $municipal_registration,

    #[Rule('nullable|string')]
    public ?string $note_general,
    
    #[Rule('nullable|string|max:255')]
    public ?string $internet_page,

    #[Rule('nullable|string|min:10')]
    public ?string $created_at,

    #[Rule('nullable|string|min:10')]
    public ?string $updated_at,

    /** @var CompanyAddressDto[] */
    public DataCollection $company_address,
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
    // Formatar CPF/CNPJ
    $company_ein = formatCpfCnpj(request()->get('company_ein', ''));
    request()->merge(['company_ein' => $company_ein]);
    
    $validator->after(function ($validator) use ($company_ein) {
      // Validar CPF ou CNPJ
      if (!cpfOrCnpjIsValid($company_ein)) {
        $validator->errors()->add('company_ein', __('company_lang.ein_is_not_valid', ['value' => $company_ein]));
      }

      // Endereço não pode ser nulo
      $company_address = request()->get('company_address');
      if (!$company_address) {
        $validator->errors()->add('company_address', __('company_lang.company_address_can_not_be_null'));
      } else {
        // Endereço deve conter um único registro como padrão.
        $address_filtered = array_filter(
          $company_address ?? [],
          function ($item) {
            return $item['is_default'] == 1;
          }
        );
        if (count($address_filtered) !== 1) {
          $validator->errors()->add('company_address', __('company_lang.company_address_must_have_single_record_default'));
        }
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
