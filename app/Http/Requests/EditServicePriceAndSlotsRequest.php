<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditServicePriceAndSlotsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'services' => 'required|array',
            'services.*.id' => 'required|integer|exists:services,id',
            'services.*.price' => 'required|integer',
            'services.*.slots' => 'required|integer',
        ];
    }
}
