<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'barber_id' => 'required|exists:barbers,id',
            'start_time' => 'required',
            'services' => 'required|array|min:1',
            'barbershop_id' => 'required|exists:barbershops,id',
            'services.*.name' => 'required|string|exists:services,name',
            'services.*.variation_name' => 'nullable|string',
        ];
    }
}
