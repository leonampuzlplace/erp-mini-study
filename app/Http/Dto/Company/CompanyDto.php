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
    public string $business_name,

    #[Rule('required|string|max:80')]
    public string $alias_name,

    public string $ein,

    #[Rule('nullable|string|max:20')]
    public ?string $state_registration,

    #[Rule('nullable|boolean')]
    public ?bool $icms_taxpayer,
    
    #[Rule('nullable|string|max:20')]
    public ?string $municipal_registration,

    #[Rule('nullable|string')]
    public ?string $note,
    
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
      'ein' => [
        'required', 
        'string',
        'max:20',
        ValidationRule::unique('company', 'ein')->ignore(request()->route('company')),
      ],
    ];
  }  

  public static function withValidator(Validator $validator): void
  {
    $validator->after(function ($validator) {
      // Company - CPF/CNPJ
      $ein = request()->get('ein', '');
      if (!cpfOrCnpjIsValid($ein)) {
        $validator->errors()->add('ein', trans('request_validation_lang.field_is_not_valid', ['value' => $ein]));
      }

      // CompanyAddress[]
      $addresses = request()->get('company_address');
      if ($addresses) {
        // Endereço deve conter um único registro como padrão.
        if (count(array_filter($addresses ?? [], fn ($i) => ($i['is_default'] ?? 0) == 1)) !== 1) {
          $validator->errors()->add('company_address', trans('request_validation_lan.array_must_have_single_record_default'));
        }
      } else {
        // Endereço não pode ser nulo
        $validator->errors()->add('company_address', trans('request_validation_lang.array_can_not_be_null'));
      }

      // CompanyContact[]
      $contacts = request()->get('company_contact');
      if ($contacts) {
        $contactsCountDefault = 0;
        foreach ($contacts as $key => $value) {
          $fieldName = 'company_contact.'.$key.'.';

          // Documento ou Telefone ou Email precisa estar preenchido
          if ((!($value['name'] ?? ''))
          &&  (!($value['phone'] ?? ''))
          &&  (!($value['email'] ?? ''))) {
            $validator->errors()->add($fieldName.'name|phone|email', trans('request_validation_lang.at_least_one_field_must_be_filled'));
          }

          // Validar CPF/CNPJ
          $ein = $value['ein'] ?? '';
          if (!cpfOrCnpjIsValid($ein)) {
            $validator->errors()->add($fieldName . 'ein', trans('request_validation_lang.field_is_not_valid', ['value' => $ein]));
          }

          // Contagem de registros com campo is_default=true
          if ($value['is_default'] ?? false) {
            $contactsCountDefault++;
          }
        }

        // Contato deve conter um único registro como padrão.
        if ($contactsCountDefault <> 1) {
          $validator->errors()->add('company_contact', trans('request_validation_lan.array_must_have_single_record_default'));
        }
      } else {
        // Contato não pode ser nulo
        $validator->errors()->add('company_contact', trans('request_validation_lang.array_can_not_be_null'));
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
      'ein' => formatCpfCnpj(request()->get('ein', ''))
    ]);
  }

  public static function formatRequestInputCompanyContact(): void
  {
    // CompanyContact[] - CPF/CNPJ
    $company_contact = request()->get('company_contact');
    if ($company_contact) {
      $companyContactFormated = array_map(
        function ($item) {
          $item['ein'] = formatCpfCnpj($item['ein'] ?? '');
          return $item;
        },
        $company_contact
      );

      request()->merge([
        'company_contact' => $companyContactFormated
      ]);
    }
  }
}
