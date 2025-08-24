<?php
namespace App\Http\Controllers\Api\User;

use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\User\RefundRequestService;
use App\Http\Resources\User\RefundRequestResource;
use App\Http\Requests\Api\User\RefundRequest\StoreRefundRequest;

class RefundRequestController extends Controller
{
    use HttpResponse;
    public function __construct(public RefundRequestService $orderService)
    {}


    public function show($id)
    {
        $data = $this->orderService->show($id);

        return $this->okResponse(new RefundRequestResource($data), __('Refund Request successfully', [], request()->header('Accept-language')));
    }

    public function store(StoreRefundRequest $request)
    {
        $data = $this->orderService->store($request->validated());

        return $this->okResponse([], __('Refund Request created successfully', [], request()->header('Accept-language')));
    }
}
