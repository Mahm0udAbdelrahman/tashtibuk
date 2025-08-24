<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Withdrawal\WithdrawalRequest;
use App\Http\Resources\Dashboard\WithdrawalDeliveryResource;
use App\Services\Dashboard\WithdrawalDeliveryService;
use App\Traits\HttpResponse;

class WithdrawalDeliveryController extends Controller
{
    use HttpResponse;
    public function __construct(public WithdrawalDeliveryService $withdrawalDeliveryService)
    {}

    public function index()
    {
        $data = $this->withdrawalDeliveryService->index();
        return $this->paginatedResponse($data, WithdrawalDeliveryResource::class);
    }

    public function update($id, WithdrawalRequest $request)
    {
        $data = $this->withdrawalDeliveryService->update($id, $request->validated());
        return $this->okResponse(new WithdrawalDeliveryResource($data), __('Create WithdrawalDelivery', [], request()->header('Accept-language')));
    }

    public function destroy(string $id)
    {
        $data = $this->withdrawalDeliveryService->destroy($id);
        return $this->okResponse([], __('Withdrawal Delivery deleted successfully', [], request()->header('Accept-language')));
    }

}
