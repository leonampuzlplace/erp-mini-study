<?php

namespace App\Http\Dto\City;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class CityDto extends Data
{
  public static function authorize(): bool
  {
    return true;
  }  

  public function __construct(
    #[Rule('nullable|integer')]
    public ?int $id,

    #[Rule('required|string|max:255')]
    public string $city_name,

    #[Rule('required|string|max:20')]
    public string $ibge_code,

    #[Rule('required|integer')]
    public int $state_id,

    #[Rule('nullable')]
    public object|array|null $state,
  ) {
  }

  // public static function rules(): array
  // {
  //   return [
  //   ];
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
  //   return [
  //   ];
  // }
}
