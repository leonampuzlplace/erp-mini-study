<?php

namespace App\Http\Dto\Person;

use Illuminate\Validation\Rule as ValidationRule;
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

    #[Rule('nullable|integer')]
    public ?int $tenant_id,

    #[Rule('nullable|string|max:80')]
    public ?string $business_name,

    #[Rule('required|string|max:80')]
    public string $alias_name,

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

    #[Rule('nullable|boolean')]
    public ?bool $is_customer,

    #[Rule('nullable|boolean')]
    public ?bool $is_seller,

    #[Rule('nullable|boolean')]
    public ?bool $is_supplier,

    #[Rule('nullable|boolean')]
    public ?bool $is_carrier,

    #[Rule('nullable|boolean')]
    public ?bool $is_technician,

    #[Rule('nullable|boolean')]
    public ?bool $is_employee,

    #[Rule('nullable|boolean')]
    public ?bool $is_other,

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
      'ein' => [
        'nullable',
        'string',
        'max:20',
        ValidationRule::unique('person', 'ein')
          ->ignore(request()->route('person'))
          ->where(function ($query) {
            return $query->where('tenant_id', '=', currentTenantId());
          }),
      ],
    ];
  }   

  public static function withValidator(Validator $validator): void
  {
    $validator->after(function ($validator) {
      // Person - CPF/CNPJ
      $ein = request()->get('ein', '');
      if (($ein) && (!cpfOrCnpjIsValid($ein))) {
        $validator->errors()->add('ein', trans('request_validation_lang.field_is_not_valid', ['value' => $ein]));
      }

      // Person - Tipo de Pessoa é obrigatório
      if ((!request()->get('is_customer') ?? false)
      &&  (!request()->get('is_seller') ?? false)
      &&  (!request()->get('is_supplier') ?? false)
      &&  (!request()->get('is_carrier') ?? false)
      &&  (!request()->get('is_technician') ?? false)
      &&  (!request()->get('is_employee') ?? false)
      &&  (!request()->get('is_other') ?? false)
      ){
        $validator->errors()->add('is_customer|is_seller|is_supplier|...', trans('request_validation_lang.at_least_one_field_must_be_filled'));
      }

      // PersonAddress[]
      $addresses = request()->get('person_address');
      if ($addresses) {
        // Endereço deve conter um único registro como padrão.
        if (count(array_filter($addresses ?? [], fn ($i) => ($i['is_default'] ?? 0) == 1)) !== 1) {
          $validator->errors()->add('person_address', trans('request_validation_lang.array_must_have_single_record_default'));
        }
      } else {
        // Endereço não pode ser nulo
        $validator->errors()->add('person_address', trans('request_validation_lang.array_can_not_be_null'));
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
          ){
            $validator->errors()->add($fieldName . 'name|phone|email', trans('request_validation_lang.at_least_one_field_must_be_filled'));
          }

          // Validar CPF/CNPJ
          $ein = $value['ein'] ?? '';
          if (($ein) && (!cpfOrCnpjIsValid($ein))) {
            $validator->errors()->add($fieldName . 'ein', trans('request_validation_lang.field_is_not_valid', ['value' => $ein]));
          }

          // Contagem de registros com campo is_default=true
          if ($value['is_default'] ?? false) {
            $contactsCountDefault++;
          }
        }

        // Contato deve conter um único registro como padrão.
        if ($contactsCountDefault <> 1) {
          $validator->errors()->add('person_contact', trans('request_validation_lang.array_must_have_single_record_default'));
        }
      } else {
        // Contato não pode ser nulo
        $validator->errors()->add('person_contact', trans('request_validation_lang.array_can_not_be_null'));
      }      
    });
  }

  public static function formatRequestInput(): void
  {
    static::formatRequestInputPerson();
    static::formatRequestInputPersonContact();
  }

  public static function formatRequestInputPerson(): void
  {
    // Person - CPF/CNPJ
    request()->merge([
      'ein' => formatCpfCnpj(request()->get('ein', ''))
    ]);
  }

  public static function formatRequestInputPersonContact(): void
  {
    // PersonContact[] - CPF/CNPJ
    $person_contact = request()->get('person_contact');
    if ($person_contact) {
      $personContactFormated = array_map(
        function ($item) {
          $item['ein'] = formatCpfCnpj($item['ein'] ?? '');
          return $item;
        },
        $person_contact
      );

      request()->merge([
        'person_contact' => $personContactFormated
      ]);
    }
  }
}
