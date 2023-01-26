<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticateValidation extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ];
    }

    public function messages()
    {
        return [

            'email.required' => 'Campo obrigátorio.',
            'password.required' => 'Campo obrigátorio.',
            'password.min' => 'no minímo 6 caracteres.'
        ];
    }
}
