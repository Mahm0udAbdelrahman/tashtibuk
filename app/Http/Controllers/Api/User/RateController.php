<?php
namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Rate\RateRequest;
use App\Services\User\RateService;
use App\Traits\HttpResponse;

class RateController extends Controller
{
    use HttpResponse;
    public function __construct(public RateService $RateService)
    {}

    public function store($vendor_id, RateRequest $request)
    {

        $this->RateService->store($vendor_id, $request->validated());
        return $this->okResponse('', __('Rating Submitted successfully'));

    }
}
