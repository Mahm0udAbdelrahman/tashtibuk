<?php
namespace App\Http\Controllers\Api\Delivery;

use App\Http\Controllers\Controller;
use App\Http\Resources\Delivery\WalletDeliveryResource;
use App\Services\Delivery\WalletService;
use App\Traits\HttpResponse;

class WalletController extends Controller
{
    use HttpResponse;

    public function __construct(public WalletService $walletService)
    {
    }

    public function index()
    {
        $data     = $this->walletService->index();
        $response = [
            'data'        => WalletDeliveryResource::collection($data),
            'count_order' => $data->sum(fn($order) => $order->items->count()),
            'balance'     => auth('delivery')->user()->balance ?? "0",
        ];
        return $this->okResponse($response, __('Wallet details retrieved successfully', [], request()->header('Accept-language')));

    }

}
