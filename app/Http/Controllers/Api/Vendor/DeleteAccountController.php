<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Vendor\DeleteAccountService;

class DeleteAccountController extends Controller
{
    use   HttpResponse;
    public function __construct(public DeleteAccountService $deleteAccountService){}

    public function deleteAccount()
    {
        $delete_account = $this->deleteAccountService->deleteAccount();
        // return $this->okResponse(null, __('Account deleted successfully', [], request()->header('Accept-language')));
                return response()->json(['message' => __('Account deleted successfully') , 'status'=> true], 200);

    }
}
