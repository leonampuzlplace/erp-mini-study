<?php

namespace App\Http\Dto\State;

use Illuminate\Validation\Validator;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class StateDto extends Data
{
  public static function authorize(): bool
  {
    return true;
  }  

  public function __construct(
    #[Rule('nullable|integer')]
    public ?int $id,

    #[Rule('required|string|max:50')]
    public string $state_name,

    #[Rule('required|string|max:2')]
    public string $state_abbreviation,
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
