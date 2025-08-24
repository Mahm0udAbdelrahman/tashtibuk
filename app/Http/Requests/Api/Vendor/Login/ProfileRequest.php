<?php

namespace App\Http\Requests\Api\Vendor\Login;

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
        $vendor = auth('vendor')->user();
        return [
            'name'         => ['nullable', 'string', 'max:255'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'email'        => ['nullable', 'email', 'unique:vendors,email,' . $vendor->id],
            'password'     => ['nullable', 'string', 'min:6'],
            'shop_name'    => ['nullable', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'shop_phone'   => ['nullable', 'string', 'max:20'],
            'lat'          => ['nullable', 'string', 'max:50'],
            'lng'          => ['nullable', 'string', 'max:50'],
            'address'      => ['nullable', 'string', 'max:255'],
            'logo'         => ['nullable', 'file'],
            'background'   => ['nullable', 'file'],
            'to'           => ['nullable', 'string', 'max:255'],
            'form'         => ['nullable', 'string', 'max:255'],
            'id_card'      => ['nullable', 'file'],
            'is_delivery'  => ['nullable', 'in:0,1'],

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
