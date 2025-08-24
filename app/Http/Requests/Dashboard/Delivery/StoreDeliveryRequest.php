<?php
namespace App\Http\Requests\Dashboard\Delivery;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeliveryRequest extends FormRequest
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
            'name'             => ['required', 'string', 'max:255'],
            'phone'            => ['required', 'string', 'unique:deliveries,phone'],
            'email'            => ['required', 'email', 'unique:deliveries,email'],
            'password'         => ['required', 'string', 'min:6'],
            'image'            => ['nullable', 'image'],
            'type_motorcycles' => ['required', 'string', 'max:255'],
            'id_card'          => ['required', 'image'],
            'driving_license'  => ['required', 'image'],
            'vehicle_license'  => ['required', 'image'],
            'lng'              => ['nullable', 'string', 'max:20'],
            'lat'              => ['nullable', 'string', 'max:20'],
            'address'          => ['required', 'string', 'max:255'],
            'is_active'        => ['required', 'in:0,1'],
            'status'           => ['required', 'in:0,1'],
        ];
    }
}
