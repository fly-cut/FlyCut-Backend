<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBarberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'barbershop_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];
    }
}
