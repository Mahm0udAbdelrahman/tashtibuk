<?php

namespace App\Services\Vendor;

use App\Models\User;


class DeleteAccountService
{

    public function deleteAccount()
    {
        $delete_account = auth()->guard('vendor')->user();
        $delete_account->delete();
        return response()->json(['message' => __('Account deleted successfully'),'status'=>true], 200);
    }
}
