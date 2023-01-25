<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendarValidation extends FormRequest
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
            'acess_number' => 'required',
            'dados' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'acess_number.required' => 'Campo obrigatorio',
            'dados.required' => 'Campo obrigatorio',
        ];
    }
}
