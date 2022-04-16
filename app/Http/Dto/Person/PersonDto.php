<?php

namespace App\Http\Dto\Person;

use Illuminate\Validation\Validator;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class PersonDto extends Data
{
  public static function authorize(): bool
  {
    return true;
  }  

  public function __construct(
    #[Rule('nullable|integer')]
    public ?int $id,

    #[Rule('required|integer|exists:company,id')]
    public int $company_id,

    #[Rule('nullable|string|max:80')]
    public ?string $business_name,

    #[Rule('required|string|max:80')]
    public string $alias_name,

    #[Rule('required|string|max:20')]
    public ?string $ein,

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

    /** @var PersonAddressDto[] */
    public DataCollection $person_address,

    /** @var PersonContactDto[] */
    public DataCollection $person_contact,
  ) {
  }

  public static function rules(): array
  {
    static::formatRequestInput();
    return [
    ];
  }

  public static function withValidator(Validator $validator): void
  {
    $validator->after(function ($validator) {
      // Person - CPF/CNPJ
      $ein = request()->get('ein', '');
      if (($ein) && (!cpfOrCnpjIsValid($ein))) {
        $validator->errors()->add('ein', trans('company_lang.ein_is_not_valid', ['value' => $ein]));
      }

      // PersonAddress[]
      $addresses = request()->get('person_address');
      if ($addresses) {
        // Endereço deve conter um único registro como padrão.
        if (count(array_filter($addresses ?? [], fn ($i) => ($i['is_default'] ?? 0) == 1)) !== 1) {
          $validator->errors()->add('person_address', trans('company_lang.company_address_must_have_single_record_default'));
        }
      } else {
        // Endereço não pode ser nulo
        $validator->errors()->add('person_address', trans('company_lang.company_address_can_not_be_null'));
      }

      // PersonContact[]
      $contacts = request()->get('person_contact');
      if ($contacts) {
        $contactsCountDefault = 0;
        foreach ($contacts as $key => $value) {
          $fieldName = 'person_contact.' . $key . '.';

          // Documento ou Telefone ou Email precisa estar preenchido
          if ((!($value['name'] ?? ''))
            &&  (!($value['phone'] ?? ''))
            &&  (!($value['email'] ?? ''))
          ) {
            $validator->errors()->add($fieldName . 'ein|phone|email', 'Um dos três campos precisa estar preenchido.');
          }

          // Validar CPF/CNPJ
          $ein = $value['ein'] ?? '';
          if (($ein) && (!cpfOrCnpjIsValid($ein))) {
            $validator->errors()->add($fieldName . 'ein', trans('company_lang.ein_is_not_valid', ['value' => $ein]));
          }

          // Contagem de registros com campo is_default=true
          if ($value['is_default'] ?? false) {
            $contactsCountDefault++;
          }
        }

        // Contato deve conter um único registro como padrão.
        if ($contactsCountDefault <> 1) {
          $validator->errors()->add('person_contact', trans('company_lang.company_contact_must_have_single_record_default'));
        }
      } else {
        // Contato não pode ser nulo
        $validator->errors()->add('person_contact', trans('company_lang.company_contact_can_not_be_null'));
      }      
    });
  }

  public static function formatRequestInput(): void
  {
    static::formatRequestInputPerson();
  }

  public static function formatRequestInputPerson(): void
  {
    // Person - CPF/CNPJ
    request()->merge([
      'ein' => formatCpfCnpj(request()->get('ein', ''))
    ]);
  }
}
