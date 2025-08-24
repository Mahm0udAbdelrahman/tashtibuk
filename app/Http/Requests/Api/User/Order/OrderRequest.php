<?php
namespace App\Http\Requests\Api\User\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
        return [
            'payment_method' => 'required|in:card,wallet,cash',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'lat' => 'required',
            'lng' => 'required',
            'building_number' => 'nullable|string|max:50',
            'floor_number' => 'nullable|string|max:50',
            'apartment_number' => 'nullable|string|max:50',

        ];
    }
}
