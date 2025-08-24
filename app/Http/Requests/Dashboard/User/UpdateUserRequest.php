<?php
namespace App\Http\Requests\Dashboard\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name'             => 'nullable|string|max:255',
            'email'            => 'nullable|email,' . $this->user->id,
            'phone'            => 'required|unique:users,phone,' . $this->user->id,
            'image'            => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'password'         => 'nullable|string|min:8|confirmed',
            'fcm_token'        => 'nullable|string',
            'birthday'         => 'nullable|string|max:255',
            'apartment_number' => 'nullable|string|max:255',
            'building_number'  => 'nullable|string|max:255',
            'floor_number'     => 'nullable|string|max:255',
            'lat'              => 'nullable|string|max:255',
            'lng'              => 'nullable|string|max:255',
            'address'          => 'nullable|string|max:255',
            'role_id'          => 'nullable|exists:roles,id',
        ];
    }
}
