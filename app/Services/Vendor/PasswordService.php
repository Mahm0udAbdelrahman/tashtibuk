<?php
namespace App\Services\Vendor;

use App\Exceptions\InsuranceNotFoundException;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class PasswordService
{
    public function __construct(public Vendor $model){}

    public function forgetPassword($data)
    {
        $vendor = $this->model->where('phone', $data['phone'])->first();
        if ($vendor) {

            $vendor->update(['code' => mt_rand(1000, 9999), 'expire_at' => Carbon::now()->addMinutes(1), 'email_verified_at' => null]);

            // $data['phone']  = preg_replace('/[\x{2066}\x{2069}]/u', '', $data['phone']);
            // $country_number = ltrim($data['phone'], '+');

            // $instanceId  = "686674CAB2929";
            // $accessToken = "683d5d57d8e14";

            // $payload = [
            //     'number'       => $country_number,
            //     'type'         => 'text',
            //     'instance_id'  => $instanceId,
            //     'access_token' => $accessToken,
            //     'message'      => $vendor->code,
            // ];

            // try {
            //     $response = Http::asForm()->post('https://app.wawp.net/api/send', $payload);

            //     $responseBody = $response->body();
            // } catch (\Exception $e) {
            //     return response()->json([
            //         'message' => 'An error occurred: ' . $e->getMessage(),
            //     ], 203);
            // }
            return $vendor;
        }
        throw new InsuranceNotFoundException(__('Phone not registered', [], request()->header('Accept-language')), 400);
    }

    public function confirmationOtp($data)
    {
        $user = $this->model->where('phone',$data['phone'])->where('code', $data['otp'])
            ->where('expire_at', '>', now())
            ->first();
        if (! $user) {
            throw new InsuranceNotFoundException(__('Otp not found', [], request()->header('Accept-language')), 400);

        }
        return $user;
    }

    public function resetPassword($data)
    {
        $user = $this->model->where('phone', $data['phone'])->first();
        if ($user->code == $data['otp']) {
            $user->update(['password' => Hash::make($data['password']), 'code' => null, 'expire_at' => null, 'email_verified_at' => Carbon::now()]);
            Auth::login($user);
            return $user;
        }
        throw new InsuranceNotFoundException(__('These credentials do not match our records.', [], Request()->header('Accept-language')), 400);
    }

    public function changePassword($data)
    {
        $user = auth('vendor')->user();
        $user->update(['password' => Hash::make($data['password'])]);
        return $user;
    }

}
