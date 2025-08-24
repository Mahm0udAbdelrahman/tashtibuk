<?php
namespace App\Services\User;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use App\Exceptions\InsuranceNotFoundException;


class LoginService
{


    public function __construct(public User $user){}


        public function login($data)
    {
        $user = User::where('phone', $data['phone'])->first();
        if (!$user) {
            throw new InsuranceNotFoundException(__('These credentials dow not match our records.', [], Request()->header('Accept-language')));
        }

        if($user->email_verified_at == null){
            throw new InsuranceNotFoundException(
                __('The user account has not been verified yet', [], request()->header('Accept-Language')),
                403
            );
        }
        if ($user && Hash::check($data['password'], $user->password)) {

            $user->update(['fcm_token' => $data['fcm_token']  , 'active' => 1]);
            $token = $user->createToken("API TOKEN")->plainTextToken;
            return [$user , $token];

        }

        throw new InsuranceNotFoundException(__('These credentials do not match our records.', [], Request()->header('Accept-language')));
    }

}
