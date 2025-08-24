<?php
namespace App\Http\Requests\Api\Delivery\Login;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
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
        $delivery = auth('delivery')->user();
        return [
            'name'             => ['nullable', 'string', 'max:255'],
            'phone'            => ['nullable', 'string', 'unique:deliveries,pnone,' . $delivery->id],
            'email'            => ['nullable', 'email', 'unique:deliveries,email,' . $delivery->id],
            'password'         => ['nullable', 'string', 'min:6'],
            'type_motorcycles' => ['nullable', 'string', 'max:255'],
            'id_card'          => ['nullable', 'image'],
            'driving_license'  => ['nullable', 'image'],
            'vehicle_license'  => ['nullable', 'image'],
            'lat'              => ['nullable', 'string', 'max:50'],
            'lng'              => ['nullable', 'string', 'max:50'],
            'address'          => ['nullable', 'string', 'max:255'],
            'is_active'        => ['nullable','in:0,1']


        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => $validator->errors()->first(),
                'type'    => 'error',
                'code'    => Response::HTTP_UNPROCESSABLE_ENTITY,
                // 'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
