<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$userId,
            'password' => 'sometimes|min:6',
            'cpf_cnpj' => 'sometimes|unique:users,cpf_cnpj,'.$userId,
            'type' => 'sometimes|in:common,merchant',
            'balance' => 'sometimes|numeric|min:0'
        ];
    }
}
