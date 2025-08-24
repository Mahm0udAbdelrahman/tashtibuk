<?php
namespace App\Services\User;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use App\Exceptions\InsuranceNotFoundException;


class LogoutService
{

    public function logout()
    {
        $user = auth('sanctum')->user();
        if ($user) {
            $user->update(['active' => 0]);
            $user->tokens()->delete();
            return $user;
        }
        throw new InsuranceNotFoundException(__('These credentials do not match our records.', [], Request()->header('Accept-language')));
    }

}
