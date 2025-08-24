<?php
namespace App\Http\Controllers\Api\Delivery;

use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\Delivery\WithdrawalService;
 

class WithdrawalController extends Controller
{
    use HttpResponse;

    public function __construct(public WithdrawalService $withdrawalService)
    {
    }

    public function store()
    {
        return  $this->withdrawalService->store();
     }


}
