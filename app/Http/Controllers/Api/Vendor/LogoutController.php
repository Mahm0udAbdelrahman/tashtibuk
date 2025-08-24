<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Vendor\LogoutService;
use App\Http\Resources\Vendor\RegisterResource;

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
