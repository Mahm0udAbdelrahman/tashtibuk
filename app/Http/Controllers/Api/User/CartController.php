<?php
namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Cart\StoreCartRequest;
use App\Http\Requests\Api\User\Cart\UpdateCartRequest;
use App\Http\Resources\User\CartResource;
use App\Http\Resources\User\DetailsCartResource;
use App\Services\User\CartService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use HttpResponse;
    public function __construct(public CartService $cartService)
    {}

    
    public function index()
    {
        $data = $this->cartService->index();

        $response = [
            'data'       => CartResource::collection($data),
            'total_price' => $data->sum('total'),
            "message"     => "Data Fetched Successfully",
            "code"        => 200,
            "status"      => "success",
        ];

        return response()->json($response);
    }

    public function store(StoreCartRequest $request)
    {
        $data = $this->cartService->store($request->validated());
        return $this->okResponse(new CartResource($data));
    }

    public function update(UpdateCartRequest $request, $id)
    {
        $data = $this->cartService->update($id, $request->validated());
        return $this->okResponse(new CartResource($data));
    }
    public function destroy(string $id)
    {
        $this->cartService->destroy($id);
        return $this->okResponse(null, __('Product removed from cart successfully', [], request()->header('Accept-language')));
    }

    public function clear()
    {
        $this->cartService->clearCart();
        return $this->okResponse(null, __('Cart cleared successfully', [], request()->header('Accept-language')));
    }
    
   public function getOrderByCart()
    {
       $data = [
            'total_price' => $this->cartService->getOrderByCart()->sum('total'),
            'count_product' => $this->cartService->getOrderByCart()->count(),
            // 'delivery_service' => 15,
            'delivery_service' => $this->cartService->costDelivery(),
            'total' => $this->cartService->getOrderByCart()->sum('total') + $this->cartService->costDelivery(),

            'message' => 'Order details fetched successfully',
            'code' => 200,
            'status' => 'success',
       ];

        return response()->json($data);
    }

}
