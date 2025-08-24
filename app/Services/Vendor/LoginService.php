<?php
namespace App\Services\Vendor;

use App\Exceptions\InsuranceNotFoundException;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;

class LoginService
{

    public function __construct(public Vendor $model)
    {}

    public function login($data)
    {
        $vendor = $this->model->where('phone', $data['phone'])->first();
        
        if (! $vendor) {
            throw new InsuranceNotFoundException(__('These credentials dow not match our records.', [], Request()->header('Accept-language')));
        }

        if ($vendor->email_verified_at == null) {
            throw new InsuranceNotFoundException(
                __('The user account has not been verified yet', [], request()->header('Accept-Language')),
                403
            );
        }
         if ($vendor->status == "0") {
            throw new InsuranceNotFoundException(
                __('The user account is not active', [], request()->header('Accept-Language')),
                403
            );
        }
        if ($vendor && Hash::check($data['password'], $vendor->password)) {

            $vendor->update(['fcm_token' => $data['fcm_token'], 'active' => 1]);
            $token = $vendor->createToken("API TOKEN")->plainTextToken;
            return [$vendor, $token];

        }

        throw new InsuranceNotFoundException(__('These credentials do not match our records.', [], Request()->header('Accept-language')));
    }

}
