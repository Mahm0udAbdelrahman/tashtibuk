<?php
namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Resources\Vendor\OrderItemVendorResource;
use App\Http\Resources\Vendor\OrderVendorResource;
use App\Services\Vendor\OrderService;
use App\Traits\HttpResponse;
use App\Http\Requests\Api\Vendor\Order\UpdateStatusOrderRequest;

class OrderController extends Controller
{
    use HttpResponse;

    public function __construct(public OrderService $orderService)
    {
    }

    public function order()
    {
        $order = $this->orderService->order();
        return $this->paginatedResponse($order, OrderVendorResource::class);
    }

    public function detailsOrder($id)
    {
        $order = $this->orderService->show($id);
        $firstItem = $order->items()->first();


        $response = [
            'data'           => OrderItemVendorResource::collection($order->items),
            'is_delivery'    => $order->items()->first()->product->vendor->is_delivery ,
            'user'           => [
                'name'    => $order->user->name,
                'email'   => $order->user->email,
                'image'   => $order->user->image,
                'phone'   => $order->user->phone,
                'lat'     => $order->user->lat,
                'lng'     => $order->user->lng,
                'address' => $order->user->address,
            ],

            'vendor'         => [
                'name'    => $firstItem->product->vendor->shop_name ?? null,
                'image'   => $firstItem->product->vendor->logo ?? null,
                'phone'   => $firstItem->product->vendor->shop_phone ?? null,
                'address' => $firstItem->product->vendor->address ?? null,
                'lat'     => $firstItem->product->vendor->lat ?? null,
                'lng'     => $firstItem->product->vendor->lng ?? null,
            ],
            'address'        => $order->address,
            'status'        => $order->status,
            'price'          => $order->price_before_percentage,
            'cost_delivery' => $order->cost_delivery,
           'total' => (string) ($order->price_before_percentage + $order->cost_delivery),

            'payment_method' => $order->payment_method,
            'created_at'     => $order->created_at->format('Y-m-d H:i:s'),
        ];

        return response()->json([
            'data'    => $response,
            'message' => 'Data Fetched Successfully',
            'code'    => 200,
            'status'  => 'success',
        ], 200);
    }
    
    public function updateStatus($id,UpdateStatusOrderRequest $request)
    {
        $order = $this->orderService->updateStatus($id, $request->validated());
        return $this->okResponse(new OrderVendorResource($order), __('Order status updated successfully', [], request()->header('Accept-language')));
    }

}
