<?php
namespace App\Services\Vendor;

use App\Exceptions\InsuranceNotFoundException;
use App\Models\Vendor;
use App\Traits\HasImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class RegisterService
{
    use HasImage;
    public function __construct(public Vendor $model)
    {}

    public function register($data)
    {

        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'Vendor');
        } else {
            $data['image'] = asset('default/default.png');
        }

        if (isset($data['logo'])) {
            $data['logo'] = $this->saveImage($data['logo'], 'Vendor');
        }
        if (isset($data['background'])) {
            $data['background'] = $this->saveImage($data['background'], 'Vendor');
        }
        if (isset($data['id_card'])) {
            $data['id_card'] = $this->saveImage($data['id_card'], 'Vendor');
        }

        $data['password']  = Hash::make($data['password']);
        $data['code']      = rand(1000, 9999);
        $data['expire_at'] = Carbon::now()->addMinutes(1);
        // $data['phone']     = preg_replace('/[\x{2066}\x{2069}]/u', '', $data['phone']);
        // $country_number    = ltrim($data['phone'], '+');

        // $instanceId  = "686674CAB2929";
        // $accessToken = "683d5d57d8e14";

        // $payload = [
        //     'number'       => $country_number,
        //     'type'         => 'text',
        //     'instance_id'  => $instanceId,
        //     'access_token' => $accessToken,
        //     'message'      => $data['code'],
        // ];

        // try {
        //     $response = Http::asForm()->post('https://app.wawp.net/api/send', $payload);

        //     $responseBody = $response->body();
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'message' => 'An error occurred: ' . $e->getMessage(),
        //     ], 203);
        // }

        return $this->model->create($data);
    }

    public function verify($data)
    {
        $vendor = $this->model->where('phone', $data['phone'])->first();

        if (! $vendor) {
            throw new InsuranceNotFoundException(__('Phone not registered', [], request()->header('Accept-language')), 400);

        }

        if ($vendor->email_verified_at) {
            throw new InsuranceNotFoundException(__('The Vendor account has already been verified', [], request()->header('Accept-language')), 400);
        }

        if ($vendor->code !== $data['otp']) {
            throw new InsuranceNotFoundException(__('Wrong OTP code', [], request()->header('Accept-language')), 400);
        }

        if (Carbon::parse($vendor->expire_at)->lt(Carbon::now())) {
            throw new InsuranceNotFoundException(__('The OTP code has expired', [], request()->header('Accept-language')), 400);
        }

        $token = $vendor->createToken("API TOKEN")->plainTextToken;
        $vendor->update([
            'email_verified_at' => Carbon::now(),
            'code'              => null,
            'expire_at'         => null,
        ]);

        // Auth::guard('vendor')->login($vendor);

        return [$vendor, $token];
    }

    public function otp($data)
    {
        $vendor = $this->model->where('phone', $data['phone'])
            ->whereNotNull('code')
            ->whereNotNull('expire_at')
            ->first();

        if ($vendor) {
            if (now()->greaterThan($vendor->expire_at)) {
                $newCode        = rand(1000, 9999);
            
                $vendor->update([
                    'code'      => $newCode,
                    'expire_at' => now()->addMinutes(1),
                ]);
            }
            return $vendor;
        }
        throw new InsuranceNotFoundException(__('The Phone is Verify', [], request()->header('Accept-language')), 400);

    }

}
