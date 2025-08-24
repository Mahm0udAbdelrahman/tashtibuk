<?php
namespace App\Http\Requests\Api\Vendor\Register;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class RegisterRequest extends FormRequest
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
            'name'        => ['required', 'string', 'max:255'],
            'phone'       => ['required', 'string','unique:vendors,phone'],
            'email'       => ['required', 'email', 'unique:vendors,email'],
            'password'    => ['required', 'string', 'min:8'],
            'shop_name'   => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'shop_phone'  => ['required', 'string', 'unique:vendors,shop_phone'],
            'lng'         => ['required', 'string', 'max:20'],
            'lat'         => ['required', 'string', 'max:20'],
            'address'     => ['required', 'string', 'max:255'],
            'logo'        => ['required', 'file'],
            'background'  => ['required', 'file'],
            'id_card'     => ['required', 'file'],
            'is_delivery' => ['required', 'in:0,1'],
            'fcm_token'   => ['nullable', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => $validator->errors()->first(),
                'type'    => 'error',
                'code'    => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
    // 'errors' => $validator->errors(),
}
