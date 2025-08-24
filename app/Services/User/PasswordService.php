<?php
namespace App\Services\User;

use App\Exceptions\InsuranceNotFoundException;
use App\Helpers\OTPHelper;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPEmail;



class PasswordService
{

    public function forgetPassword($data)
    {
        $user = User::where('phone', $data['phone'])->first();
        if ($user) {

            $user->update(['code' => mt_rand(1000, 9999), 'expire_at' => Carbon::now()->addMinutes(1), 'email_verified_at' => null]);

        // Mail::to($data['email'])->send(new OTPEmail($user->code));
            return $user;
       }
        throw new InsuranceNotFoundException(__('Phone not registered', [], request()->header('Accept-language')), 400);
    }

    public function confirmationOtp($data)
    {
        $user = User::where('phone',$data['phone'])->where('code', $data['otp'])
            ->where('expire_at', '>', now())
            ->first();
        if (! $user) {
            throw new InsuranceNotFoundException(__('Otp not found', [], request()->header('Accept-language')), 400);

        }
        return $user;
    }


    public function resetPassword($data)
    {
        $user = User::where('phone', $data['phone'])->first();
        if ($user->code == $data['otp']) {
            $user->update(['password' => Hash::make($data['password']), 'code' => null, 'expire_at' => null, 'email_verified_at' => Carbon::now()]);
            Auth::login($user);
            return $user;
        }
        throw new InsuranceNotFoundException(__('These credentials do not match our records.', [], Request()->header('Accept-language')), 400);
    }

    public function changePassword($data)
    {
        $user = auth('sanctum')->user();
        $user->update(['password' => Hash::make($data['password'])]);
        return $user;
    }

}
