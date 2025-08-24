<?php

namespace App\Services\Delivery;

use App\Models\User;


class DeleteAccountService
{

    public function deleteAccount()
    {
        $delete_account = auth()->guard('delivery')->user();
        $delete_account->delete();
        return response()->json(['message' => __('Account deleted successfully'),'status'=>true], 200);
    }
}
