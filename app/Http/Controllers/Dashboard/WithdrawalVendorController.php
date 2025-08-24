<?php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Withdrawal\WithdrawalRequest;
use App\Http\Resources\Dashboard\WithdrawalVendorResource;
use App\Services\Dashboard\WithdrawalVendorService;
use App\Traits\HttpResponse;

class WithdrawalVendorController extends Controller
{
    use HttpResponse;
    public function __construct(public WithdrawalVendorService $withdrawalVendorService)
    {}

    public function index()
    {
        $data = $this->withdrawalVendorService->index();
        return $this->paginatedResponse($data, WithdrawalVendorResource::class);
    }

    public function update($id, WithdrawalRequest $request)
    {
        $data = $this->withdrawalVendorService->update($id, $request->validated());
        return $this->okResponse(new WithdrawalVendorResource($data), __('Create Setting', [], request()->header('Accept-language')));
    }

    public function destroy(string $id)
    {
        $data = $this->withdrawalVendorService->destroy($id);
        return $this->okResponse([], __('Withdrawal Vendor deleted successfully', [], request()->header('Accept-language')));
    }

}
