<?php
namespace App\Services\Delivery;

use App\Exceptions\InsuranceNotFoundException;
use App\Models\Delivery;
use Illuminate\Support\Facades\Hash;

class LoginService
{

    public function __construct(public Delivery $model)
    {}

    public function login($data)
    {
        $delivery = $this->model->where('phone', $data['phone'])->first();
       
        if (! $delivery) {
            throw new InsuranceNotFoundException(__('These credentials dow not match our records.', [], Request()->header('Accept-language')));
        }

        if ($delivery->email_verified_at == null) {
            throw new InsuranceNotFoundException(
                __('The user account has not been verified yet', [], request()->header('Accept-Language')),
                403
            );
        }
        if ($delivery->status !== "1") {
            throw new InsuranceNotFoundException(
                __('The delivery account is not active', [], request()->header('Accept-Language')),
                403
            );
        }
        if ($delivery && Hash::check($data['password'], $delivery->password)) {

            $delivery->update(['fcm_token' => $data['fcm_token'], 'active' => 1]);
            $token = $delivery->createToken("API TOKEN")->plainTextToken;
            return [$delivery, $token];

        }

        throw new InsuranceNotFoundException(__('These credentials do not match our records.', [], Request()->header('Accept-language')));
    }

}
