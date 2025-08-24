<?php
namespace App\Services\User;

use App\Exceptions\InsuranceNotFoundException;
use App\Models\User;
use App\Traits\HasImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class RegisterService
{
    use HasImage;
    public function __construct(public User $user)
    {}

    // public function register($data)
    // {

    //     if (isset($data['image'])) {
    //         $data['image'] = $this->saveImage($data['image'], 'user');
    //     } else {
    //         $data['image'] = asset('default/default.png');
    //     }

    //     $data['password']  = Hash::make($data['password']);
    //     $data['code']      = rand(1000, 9999);
    //     $data['expire_at'] = Carbon::now()->addMinutes(5);
    //     // $data['phone']     = preg_replace('/[\x{2066}\x{2069}]/u', '', $data['phone']);
    //     // $country_number    = ltrim($data['phone'], '+');

    //     // $instanceId  = "686674CAB2929";
    //     // $accessToken = "683d5d57d8e14";

    //     // $payload = [
    //     //     'number'       => $country_number,
    //     //     'type'         => 'text',
    //     //     'instance_id'  => $instanceId,
    //     //     'access_token' => $accessToken,
    //     //     'message'      => $data['code'],
    //     // ];

    //     // try {
    //     //     $response = Http::asForm()->post('https://app.wawp.net/api/send', $payload);

    //     //     $responseBody = $response->body();
    //     // } catch (\Exception $e) {
    //     //     return response()->json([
    //     //         'message' => 'An error occurred: ' . $e->getMessage(),
    //     //     ], 203);
    //     // }

    //     return $this->user->create($data);
    // }
        public function register($data)
{
    DB::beginTransaction();

    if (isset($data['image'])) {
        $data['image'] = $this->saveImage($data['image'], 'user');
    } else {
        $data['image'] = asset('default/default.png');
    }

    $data['password'] = Hash::make($data['password']);
    $data['code'] = rand(1000, 9999);
    $data['expire_at'] = Carbon::now()->addMinutes(1);

      $otp = $data['code'];
      $phone_number = $data['phone'];
        if($otp){
        $country_number = ltrim($phone_number, '+');
        $accessToken = "EAAS5MlkkxCYBPF97ioY1csDcSezQgL8pphYjpbrZB3QDDzHBmi55n5mIP8RxzyQeyi4L58wECVuoZAb5aaEcr11bTJ2ikdsHE3dKdk7wCBOnnsUElLQFFotVx2ZCcz7b6MQFI1YCcQZC3HszqcgcLaMSevWkGNieog2oQ5p0qZB4ZBkBOTRWr1b6gJavYEwBiKBTIyAoXNXELaMov0ZAzyBpCZAC0uRoNPjFWWN29DE7";
        
        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $country_number,
            'type' => 'template',
            'template' => [
                'name' => 'brmja_otp',
                'language' => [
                    'code' => false ? 'ar' : 'en'
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => (string)$otp
                            ]
                        ]
                    ],
                    [
                        'type' => 'button',
                        'sub_type' => 'url',
                        'index' => 0,
                        'parameters' => [
                            [
                                'type' => 'text',
                                'text' => (string)$otp
                            ]
                        ]
                    ]
                ]
            ]
        ];
        
      

        try {
            $response = Http::withToken($accessToken)->post('https://graph.facebook.com/v22.0/662851536920543/messages', $payload);
              
            
            if (!$response->successful()) {
                return response()->json([
                    'message' => 'An error occurred: ' . $response->body(),
                ], 402);
            }

            // return res_data($code, $lang == 'ar' ? 'تم التسجيل بنجاح' : 'Registration successful', 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 402);
        }
    }

    $user = $this->user->create($data);
    DB::commit();

    return $user;
}


    public function verify($data)
    {
        $user = User::where('phone', $data['phone'])->first();

        if (! $user) {
            throw new InsuranceNotFoundException(__('Phone not registered', [], request()->header('Accept-language')), 400);

        }

        if ($user->email_verified_at) {
            throw new InsuranceNotFoundException(__('The user account has already been verified', [], request()->header('Accept-language')), 400);
        }

        if ($user->code !== $data['otp']) {
            throw new InsuranceNotFoundException(__('Wrong OTP code', [], request()->header('Accept-language')), 400);
        }

        if (Carbon::parse($user->expire_at)->lt(Carbon::now())) {
            throw new InsuranceNotFoundException(__('The OTP code has expired', [], request()->header('Accept-language')), 400);
        }

        $token = $user->createToken("API TOKEN")->plainTextToken;
        $user->update([
            'email_verified_at' => Carbon::now(),
            'code'              => null,
            'expire_at'         => null,
        ]);

        Auth::login($user);

        return [$user, $token];
    }

    public function otp($data)
    {
        $user = User::where('phone', $data['phone'])
            ->whereNotNull('code')
            ->whereNotNull('expire_at')
            ->first();

        if ($user) {
            if (now()->greaterThan($user->expire_at)) {
                $newCode        = rand(1000, 9999);
                $data['phone']  = preg_replace('/[\x{2066}\x{2069}]/u', '', $data['phone']);
                $country_number = ltrim($data['phone'], '+');

                $instanceId  = "686674CAB2929";
                $accessToken = "683d5d57d8e14";

                $payload = [
                    'number'       => $country_number,
                    'type'         => 'text',
                    'instance_id'  => $instanceId,
                    'access_token' => $accessToken,
                    'message'      => $newCode,
                ];

                try {
                    $response = Http::asForm()->post('https://app.wawp.net/api/send', $payload);

                    $responseBody = $response->body();
                } catch (\Exception $e) {
                    return response()->json([
                        'message' => 'An error occurred: ' . $e->getMessage(),
                    ], 203);
                }
                $user->update([
                    'code'      => $newCode,
                    'expire_at' => now()->addMinutes(1),
                ]);
            }
            return $user;
        }
        throw new InsuranceNotFoundException(__('The Phone is Verify', [], request()->header('Accept-language')), 400);

        // Mail::to($data['email'])->send(new OTPEmail($user->code));

    }

}
