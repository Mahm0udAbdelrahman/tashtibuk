<?php
namespace App\Http\Controllers\Api\Delivery;

use App\Traits\HasImage;
use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\Delivery\LoginService;
use App\Http\Resources\Delivery\RegisterResource;
use App\Http\Requests\Api\Delivery\Login\LoginRequest;

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
