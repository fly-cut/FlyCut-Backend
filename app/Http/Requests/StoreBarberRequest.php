<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarberRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ];
    }
}
