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

    #[Rule('nullable|boolean')]
    public ?bool $icms_taxpayer,
    
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

    /** @var CompanyContactDto[] */
    public DataCollection $company_contact,
  ){
  }

  public static function rules(): array
  {
    Static::formatRequestInput();
    return [
      'company_ein' => [
        'required', 
        'string',
        'max:20',
        ValidationRule::unique('company', 'company_ein')->ignore(request()->route('company')),
      ],
    ];
  }  

  public static function withValidator(Validator $validator): void
  {
    $validator->after(function ($validator) {
      // Company - CPF/CNPJ
      $company_ein = request()->get('company_ein', '');
      if (!cpfOrCnpjIsValid($company_ein)) {
        $validator->errors()->add('company_ein', trans('company_lang.ein_is_not_valid', ['value' => $company_ein]));
      }

      // CompanyAddress[]
      $addresses = request()->get('company_address');
      if ($addresses) {
        // Endereço deve conter um único registro como padrão.
        if (count(array_filter($addresses ?? [], fn ($i) => ($i['is_default'] ?? 0) == 1)) !== 1) {
          $validator->errors()->add('company_address', trans('company_lang.company_address_must_have_single_record_default'));
        }
      } else {
        // Endereço não pode ser nulo
        $validator->errors()->add('company_address', trans('company_lang.company_address_can_not_be_null'));
      }

      // CompanyContact[]
      $contacts = request()->get('company_contact');
      if ($contacts) {
        $contactsCountDefault = 0;
        foreach ($contacts as $key => $value) {
          $fieldName = 'company_contact.'.$key.'.';

          // Documento ou Telefone ou Email precisa estar preenchido
          if ((!($value['contact_name'] ?? ''))
          &&  (!($value['phone'] ?? ''))
          &&  (!($value['email'] ?? ''))) {
            $validator->errors()->add($fieldName.'company_ein|phone|email', 'Um dos três campos precisa estar preenchido.');
          }

          // Validar CPF/CNPJ
          $contact_ein = $value['contact_ein'] ?? '';
          if (!cpfOrCnpjIsValid($contact_ein)) {
            $validator->errors()->add($fieldName . 'contact_ein', trans('company_lang.ein_is_not_valid', ['value' => $contact_ein]));
          }

          // Contagem de registros com campo is_default=true
          if ($value['is_default'] ?? false) {
            $contactsCountDefault++;
          }
        }

        // Contato deve conter um único registro como padrão.
        if ($contactsCountDefault <> 1) {
          $validator->errors()->add('company_contact', trans('company_lang.company_contact_must_have_single_record_default'));
        }
      } else {
        // Contato não pode ser nulo
        $validator->errors()->add('company_contact', trans('company_lang.company_contact_can_not_be_null'));
      }
    });
  }

  public static function formatRequestInput(): void
  {
    static::formatRequestInputCompany();
    static::formatRequestInputCompanyContact();
  }

  public static function formatRequestInputCompany(): void
  {
    // Company - CPF/CNPJ
    request()->merge([
      'company_ein' => formatCpfCnpj(request()->get('company_ein', ''))
    ]);
  }

  public static function formatRequestInputCompanyContact(): void
  {
    // CompanyContact[] - CPF/CNPJ
    $company_contact = request()->get('company_contact');
    if ($company_contact) {
      $companyContactFormated = array_map(
        function ($item) {
          $item['contact_ein'] = formatCpfCnpj($item['contact_ein'] ?? '');
          return $item;
        },
        $company_contact
      );

      request()->merge([
        'company_contact' => $companyContactFormated
      ]);
    }
  }
  
  // public static function messages(): array
  // {
  //   return [
  //     'business_name.required' => 'A business_name is required',
  //     'alias_name.required' => 'An alias_name is required',
  //   ];
  // }
}
