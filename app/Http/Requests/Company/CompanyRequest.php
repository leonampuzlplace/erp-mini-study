<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'business_name' => 'required|string|max:80',
            'alias_name' => 'required|string|max:80',
            'company_ein' => 'required|string|min:14|max:20',
            'state_registration' => 'nullable|string|max:20',
            'icms_taxpayer' => ['required', 'integer', Rule::in([0, 1, 2])], // 0-Nao, 1-Sim, 2-Isento
            'municipal_registration' => 'nullable|string',
            'note_general' => 'nullable|string',
            'internet_page' => 'nullable|string|max:255',
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'required' => 'The :attribute field is required.',
    //         'same' => 'The :attribute and :other must match.',
    //         'size' => 'The :attribute must be exactly :size.',
    //         'between' => 'The :attribute value :input is not between :min - :max.',
    //         'in' => 'The :attribute must be one of the following types: :values',
    //     ];
    // }    
}
