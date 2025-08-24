<?php

namespace App\Http\Controllers\Dashboard;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\LoginService;
use Illuminate\Support\Facades\{Session,Auth};
use App\Http\Resources\Dashboard\LoginResource;
use App\Http\Requests\Dashboard\Login\LoginRequest;

class LoginController extends Controller
{
    use HttpResponse;

    public function __construct(public LoginService $loginService){}



     use HttpResponse ;



    public function login(LoginRequest $loginRequest)
    {

        [$user, $token] = $this->loginService->login($loginRequest->validated());

        $response = [
            'user'  => LoginResource::make($user),
            'token' => $token,
        ];
        return $this->okResponse($response, __('Login successfully', [], request()->header('Accept-language')));
    }
    public function logout()
    {
        $this->loginService->logout();
        return $this->okResponse('','Logout');
    }
}
