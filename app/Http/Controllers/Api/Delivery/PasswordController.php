<?php

namespace App\Http\Controllers\Api\Delivery;

use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\Delivery\PasswordService;
use App\Http\Resources\Delivery\RegisterResource;
use App\Http\Requests\Api\Delivery\Login\ChangePassword;
use App\Http\Requests\Api\Delivery\Register\SendCodeRequest;
use App\Http\Requests\Api\Delivery\Login\ResetPasswordRequest;
use App\Http\Requests\Api\Delivery\Register\ConfirmationOtpRequest;

class PasswordController extends Controller
{
    use HttpResponse;
    public function __construct(public PasswordService $passwordService){}

        public function forgetPassword(SendCodeRequest $sendCodeRequest)
        {
           $user = $this->passwordService->forgetPassword($sendCodeRequest->validated());
            $response = ['user' => RegisterResource::make($user), 'otp' => $user->code];
            return $this->okResponse($response, __('Code sent successfully', [], Request()->header('Accept-language')));

        }
        public function confirmationOtp(ConfirmationOtpRequest $confirmationOtpRequest)
        {
           $user = $this->passwordService->confirmationOtp($confirmationOtpRequest->validated());
            $response = ['otp' => $user->code];
            return $this->okResponse($response, __('Code successfully', [], Request()->header('Accept-language')));

        }


        public function resetPassword(ResetPasswordRequest $resetPasswordRequest)
        {
            $user = $this->passwordService->resetPassword($resetPasswordRequest->validated());
            return $this->okResponse(RegisterResource::make($user), __('The password has been reset successfully', [], Request()->header('Accept-language')));
        }


        public function changePassword(ChangePassword $changePassword)
        {
            $user = $this->passwordService->changePassword($changePassword->validated());
            return $this->okResponse(RegisterResource::make($user), __('The password has been changed successfully', [], Request()->header('Accept-language')));

        }




}
