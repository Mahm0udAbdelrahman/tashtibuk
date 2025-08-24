<?php

namespace App\Http\Controllers\Api\Delivery;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Delivery\DeleteAccountService;

class DeleteAccountController extends Controller
{
    use   HttpResponse;
    public function __construct(public DeleteAccountService $deleteAccountService){}

    public function deleteAccount()
    {
        $delete_account = $this->deleteAccountService->deleteAccount();
        return response()->json(['message' => __('Account deleted successfully') , 'status'=> true], 200);

    }
}
