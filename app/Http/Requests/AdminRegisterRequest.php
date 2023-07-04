<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|regex:/(^([a-zA-Z]+)?$)/u',
            'email' => 'required|string|unique:admins,email|email|max:255',
            'password' => 'required|string|confirmed|max:255',
        ];
    }
}
