<?php
namespace App\Services\Delivery;

use App\Models\User;

use Illuminate\Support\Facades\Hash;
use App\Exceptions\InsuranceNotFoundException;


class LogoutService
{

    public function logout()
    {
        $vendor = auth('delivery')->user();
        if ($vendor) {
            $vendor->tokens()->delete();
            return $vendor;
        }
        throw new InsuranceNotFoundException(__('These credentials do not match our records.', [], Request()->header('Accept-language')));
    }

}
