<?php
namespace App\Services\Delivery;

use App\Exceptions\InsuranceNotFoundException;
use App\Models\Delivery;
use App\Traits\HasImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class RegisterService
{
    use HasImage;
    public function __construct(public Delivery $model)
    {}

    public function register($data)
    {

        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'Delivery');
        } else {
            $data['image'] = asset('default/default.png');
        }

        if (isset($data['id_card'])) {
            $data['id_card'] = $this->saveImage($data['id_card'], 'Delivery');
        }
        if (isset($data['driving_license'])) {
            $data['driving_license'] = $this->saveImage($data['driving_license'], 'Delivery');
        }

         if (isset($data['vehicle_license'])) {
            $data['vehicle_license'] = $this->saveImage($data['vehicle_license'], 'Delivery');
        }

        $data['password']  = Hash::make($data['password']);
        $data['code']      = rand(1000, 9999);
        $data['expire_at'] = Carbon::now()->addMinutes(1);


        return $this->model->create($data);
    }

    public function verify($data)
    {
        $delivery = $this->model->where('phone', $data['phone'])->first();

        if (! $delivery) {
            throw new InsuranceNotFoundException(__('Phone not registered', [], request()->header('Accept-language')), 400);

        }

        if ($delivery->email_verified_at) {
            throw new InsuranceNotFoundException(__('The delivery account has already been verified', [], request()->header('Accept-language')), 400);
        }

        if ($delivery->code !== $data['otp']) {
            throw new InsuranceNotFoundException(__('Wrong OTP code', [], request()->header('Accept-language')), 400);
        }

        if (Carbon::parse($delivery->expire_at)->lt(Carbon::now())) {
            throw new InsuranceNotFoundException(__('The OTP code has expired', [], request()->header('Accept-language')), 400);
        }

        $token = $delivery->createToken("API TOKEN")->plainTextToken;
        $delivery->update([
            'email_verified_at' => Carbon::now(),
            'code'              => null,
            'expire_at'         => null,
        ]);

        // Auth::guard('delivery')->login($delivery);

        return [$delivery, $token];
    }

    public function otp($data)
    {
        $delivery = $this->model->where('phone', $data['phone'])
            ->whereNotNull('code')
            ->whereNotNull('expire_at')
            ->first();

        if ($delivery) {
            if (now()->greaterThan($delivery->expire_at)) {
                $newCode        = rand(1000, 9999);

                $delivery->update([
                    'code'      => $newCode,
                    'expire_at' => now()->addMinutes(1),
                ]);
            }
            return $delivery;
        }
        throw new InsuranceNotFoundException(__('The Phone is Verify', [], request()->header('Accept-language')), 400);

    }

}
