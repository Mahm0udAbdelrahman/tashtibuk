<?php

namespace App\Http\Requests\Dashboard\Vendor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorRequest extends FormRequest
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
           'name'        => ['nullable', 'string', 'max:255'],
            'phone'       => ['nullable', 'string','unique:vendors,phone'],
            'email'       => ['nullable', 'email', 'unique:vendors,email'],
            'password'    => ['nullable', 'string', 'min:6'],
            'shop_name'   => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'shop_phone'  => ['nullable', 'string', 'unique:vendors,shop_phone'],
            'lng'         => ['nullable', 'string', 'max:20'],
            'lat'         => ['nullable', 'string', 'max:20'],
            'address'     => ['nullable', 'string', 'max:255'],
            'logo'        => ['nullable', 'file'],
            'background'  => ['nullable', 'file'],
            'status'      => ['nullable' , 'in:0,1']

        ];
    }
}
