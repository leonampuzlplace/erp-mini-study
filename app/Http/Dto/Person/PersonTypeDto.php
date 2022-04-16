<?php

namespace App\Http\Dto\Person;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class PersonTypeDto extends Data
{
  public static function authorize(): bool
  {
    return true;
  }  

  public function __construct(
    #[Rule('nullable|integer')]
    public ?int $id,

    #[Rule('required|string|max:60')]
    public string $person_type_name,
  ) {
  }

  // public static function rules(): array
  // {
  //   return [];
  // }

  // public static function withValidator(Validator $validator): void
  // {
  //   // $validator->after(function ($validator) {
  //   //   $field = request()->get('field');
  //   //   if ($field) {
  //   //     $validator->errors()->add('field', 'Field ('. $field .') is not valid!');
  //   //   }      
  //   // });
  // }

  // public static function messages(): array
  // {
  //   return [];
  // }
}
