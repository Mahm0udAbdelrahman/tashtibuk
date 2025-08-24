<?php
namespace App\Http\Controllers\Api\Delivery;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Delivery\Login\ProfileRequest;
use App\Http\Resources\Delivery\RegisterResource;
use App\Services\Delivery\ProfileService;
use App\Traits\HttpResponse;
use App\Http\Requests\Api\Delivery\Login\LocationRequest;

class ProfileController extends Controller
{
    use HttpResponse;
    public function __construct(public ProfileService $profileService)
    {}
    public function profile()
    {
        $user = $this->profileService->profile();

        return $this->okResponse(RegisterResource::make($user), __('View profile', [], Request()->header('Accept-language')));

    }

    public function updateProfile(ProfileRequest $profileRequest)
    {
        $user = $this->profileService->updateProfile($profileRequest->validated());
        return $this->okResponse(RegisterResource::make($user), __('The user profile has been updated successfully', [], Request()->header('Accept-language')));
    }
    
     public function location(LocationRequest $locationRequest)
    {
        $user = $this->profileService->location($locationRequest->validated());
        return $this->okResponse(RegisterResource::make($user), __('The user profile has been updated successfully', [], Request()->header('Accept-language')));
    }

}
