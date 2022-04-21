<?php

namespace App\Http\Dto\Tenant;

use Illuminate\Validation\Rule as ValidationRule;
use Illuminate\Validation\Validator;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class TenantDto extends Data
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

    /** @var TenantAddressDto[] */
    public DataCollection $tenant_address,

    /** @var TenantContactDto[] */
    public DataCollection $tenant_contact,
  ){
  }

  public static function rules(): array
  {
    return [
      'ein' => [
        'required',
        'string',
        'numeric',
        ValidationRule::unique('tenant', 'ein')
        ->ignore(request()->route('tenant')),        
        fn ($att, $value, $fail) => static::rulesEin($att, $value, $fail),
      ],
    ];
  }

  // Validar CPF/CNPJ
  public static function rulesEin($att, $value, $fail)
  {
    if ($value && (!cpfOrCnpjIsValid($value))) {
      $fail(trans('request_validation_lang.field_is_not_valid', ['value' => $value]));
    }
  }

  public static function withValidator(Validator $validator): void
  {
    $validator->after(function ($validator) {
      // TenantAddress[]
      $addresses = request()->get('tenant_address');
      if (!$addresses) {
        // Endereço não pode ser nulo
        $validator->errors()->add('tenant_address', trans('request_validation_lang.array_can_not_be_null'));
      } else {
        // Endereço deve conter um único registro como padrão.
        if (count(array_filter($addresses ?? [], fn ($i) => ($i['is_default'] ?? 0) == 1)) !== 1) {
          $validator->errors()->add('tenant_address', trans('request_validation_lang.array_must_have_single_record_default'));
        }
      }

      // TenantContact[]
      $contacts = request()->get('tenant_contact');
      if (!$contacts) {
        // Contato não pode ser nulo
        $validator->errors()->add('tenant_contact', trans('request_validation_lang.array_can_not_be_null'));
      } else {      
        $contactsCountDefault = 0;
        foreach ($contacts as $key => $value) {
          $fieldName = 'tenant_contact.'.$key.'.';

          // Documento ou Telefone ou Email precisa estar preenchido
          if ((!($value['name'] ?? ''))
          &&  (!($value['phone'] ?? ''))
          &&  (!($value['email'] ?? ''))) {
            $validator->errors()->add($fieldName.'name|phone|email', trans('request_validation_lang.at_least_one_field_must_be_filled'));
          }

          // Contagem de registros com campo is_default=true
          if ($value['is_default'] ?? false) {
            $contactsCountDefault++;
          }
        }

        // Contato deve conter um único registro como padrão.
        if ($contactsCountDefault <> 1) {
          $validator->errors()->add('tenant_contact', trans('request_validation_lang.array_must_have_single_record_default'));
        }
      }
    });
  }
}
