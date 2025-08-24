<?php
namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Vendor\Login\LoginRequest;
use App\Http\Resources\Vendor\RegisterResource;
use App\Services\Vendor\LoginService;
use App\Traits\HasImage;
use App\Traits\HttpResponse;

class LoginController extends Controller
{
    use HttpResponse, HasImage;

    public function __construct(public LoginService $loginService)
    {}

    public function login(LoginRequest $loginRequest)
    {

        [$user, $token] = $this->loginService->login($loginRequest->validated());

        $response = [
            'user'  => RegisterResource::make($user),
            'token' => $token,
        ];
        return $this->okResponse($response, __('Login successfully', [], request()->header('Accept-language')));
    }
}
