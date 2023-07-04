<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetReservedSlotsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'day' => 'required|date_format:Y-m-d',
            'barber_id' => 'required|exists:barbers,id',
            'barbershop_id' => 'required|exists:barbershops,id',
        ];
    }
}
