<?php
namespace App\Http\Controllers\Api\Vendor;

use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\Vendor\WithdrawalService;
use App\Http\Resources\Vendor\OrderVendorResource;
use App\Http\Resources\Vendor\OrderItemVendorResource;
use App\Http\Requests\Api\Vendor\Order\UpdateStatusOrderRequest;

class WithdrawalController extends Controller
{
    use HttpResponse;

    public function __construct(public WithdrawalService $withdrawalService)
    {
    }

    public function store()
    {
         return $this->withdrawalService->store();
     }


}
