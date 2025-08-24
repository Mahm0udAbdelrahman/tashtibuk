<?php

namespace App\Http\Requests\Dashboard\Delivery;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeliveryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return
            [
            'name'             => ['nullable', 'string', 'max:255'],
            'phone'            => ['nullable', 'string', 'unique:deliveries,phone'],
            'email'            => ['nullable', 'email', 'unique:deliveries,email'],
            'password'         => ['nullable', 'string', 'min:6'],
            'image'            => ['nullable', 'image'],
            'type_motorcycles' => ['nullable', 'string', 'max:255'],
            'id_card'          => ['nullable', 'image'],
            'driving_license'  => ['nullable', 'image'],
            'vehicle_license'  => ['nullable', 'image'],
            'lng'              => ['nullable', 'string', 'max:20'],
            'lat'              => ['nullable', 'string', 'max:20'],
            'address'          => ['nullable', 'string', 'max:255'],
            'is_active'        => ['nullable', 'in:0,1'],
            'status'           => ['nullable', 'in:0,1'],
        ];
    }
}
