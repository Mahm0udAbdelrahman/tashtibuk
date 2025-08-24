<?php

namespace App\Http\Requests\Api\User\Login;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ProfileRequest extends FormRequest
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
        $user = auth()->user();
        return [
            'name'=>'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'birthday' => 'nullable|string|max:255',
            'apartment_number' => 'nullable|string|max:255',
            'building_number' => 'nullable|string|max:255',
            'floor_number' => 'nullable|string|max:255',
            'lat' => 'nullable|string|max:255',
            'lng' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' =>  $validator->errors()->first(),
                'type' => 'error',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
                // 'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
