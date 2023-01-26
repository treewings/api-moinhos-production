<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserValidation extends FormRequest
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

    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:usuario_administradors',
            'password' => 'required|string|min:6|max:50'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Campo obrigátorio.',
            'email.unique' => 'Esse email já existe.',
            'email.required' => 'Campo obrigátorio.',
            'email.email' => 'Campo invalido.',
            'password.required' => 'Campo obrigátorio.',
            'password.min' => 'no minímo 6 caracteres.'
        ];
    }
}
