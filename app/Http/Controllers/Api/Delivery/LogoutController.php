<?php

namespace App\Http\Controllers\Api\Delivery;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Delivery\LogoutService;
use App\Http\Resources\Delivery\RegisterResource;

class LogoutController extends Controller
{
    use HttpResponse;

    public function __construct(public LogoutService $logoutService)
    {
    }

    public function logout()
    {
        $user = $this->logoutService->logout();
        return $this->okResponse(RegisterResource::make($user), __('logout', [], Request()->header('Accept-language')));
    }


}
