<?php
namespace App\Services\User;

use App\Exceptions\InsuranceNotFoundException;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Setting;
use App\Traits\HttpResponse;

class CartService
{
    use HttpResponse;
    public function __construct(public Cart $model)
    {}

    public function index()
    {
        $userId = auth('sanctum')->user()->id;
        return $this->model->where('user_id', $userId)->with(['product'])->get();
    }

    public function store($data)
    {
        $data['user_id'] = auth('sanctum')->user()->id;

        $product = Product::findOrFail($data['product_id']);
        foreach ($product->colorSizes as $colorSize) {

             if ($colorSize->color == $data['color'] && $colorSize->size->id == $data['size_id']) {

                if ($colorSize->quantity < $data['quantity']) {

                    throw new InsuranceNotFoundException(__('The requested quantity exceeds available stock.'));
                }

            }
        }
        $data['price'] = $product->price;

        $existingCartItems = $this->model->where('user_id', $data['user_id'])->get();

        if ($existingCartItems->count() > 0) {
            $existingVendorId = $existingCartItems->first()->product->vendor_id;

            if ($product->vendor_id != $existingVendorId) {

                throw new InsuranceNotFoundException(__('You cannot add products from different vendors to the cart.'));

            }
        }

        $cart = $this->model
            ->where('user_id', $data['user_id'])
            ->where('product_id', $data['product_id'])->where('color', $data['color'])->where('size_id', $data['size_id'])
            ->first();
            
            if ($cart) {

            throw new InsuranceNotFoundException(__('This product is already in your cart.'));

        }

        if ($cart) {
            $cart->quantity += $data['quantity'];
            $cart->price = $cart->product->price;
            $cart->total = $cart->price * $cart->quantity;
            $cart->save();
            return $cart;
        }
         

        $data['total'] = $product->price * $data['quantity'];

        $total_cart = $this->model->create($data);
       $total_cart->update([
        'cost_delivery' => $this->costDelivery()
       ]);
        return $total_cart;
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, $data)
    {
        $cart = $this->model->findOrFail($id);
        foreach ($cart->product->colorSizes as $colorSize) {

            if ($colorSize->color == $cart->color && $colorSize->size->id == $cart->size_id) {

                if ($colorSize->quantity < $data['quantity']) {

                    throw new InsuranceNotFoundException(__('The requested quantity exceeds available stock.'));
                }

            }
        }
        if (isset($data['quantity'])) {
            $data['total'] = $cart->product->price * $data['quantity'];
        }
        $cart->update($data);
        return $cart;
    }

    public function destroy($id)
    {
        $cart = $this->model->findOrFail($id);
        $cart->delete();
        return $cart;
    }

    public function clearCart()
    {

        $userId = auth('sanctum')->user()->id;
        return $this->model->where('user_id', $userId)->delete();
    }

    public function getOrderByCart()
    {
        $userId = auth('sanctum')->user()->id;
        return $this->model->where('user_id', $userId)->get();
    }
    
     public function costDelivery()
{
    $userId = auth('sanctum')->user()->id;

     $user = auth('sanctum')->user();
    $userLat = $user->lat;
    $userLng = $user->lng;


    $cartItem = $this->model->where('user_id', $userId)->with('product.vendor')->first();

    if (!$cartItem) {
        throw new InsuranceNotFoundException(__('Your cart is empty.'));
    }

    $vendorLat = $cartItem->product->vendor->lat;
    $vendorLng = $cartItem->product->vendor->lng;
    
    $distanceKm = max(1, round($this->calculateDistance($userLat, $userLng, $vendorLat, $vendorLng), 2));

     
    $pricePerKm   = Setting::value('price_per_km');
    $deliveryCost = $distanceKm * $pricePerKm;
 
    return round($deliveryCost, 2);
}

 
private function calculateDistance($lat1, $lon1, $lat2, $lon2)
{
    $earthRadius = 6371;

    $latFrom = deg2rad($lat1);
    $lonFrom = deg2rad($lon1);
    $latTo = deg2rad($lat2);
    $lonTo = deg2rad($lon2);

    $latDelta = $latTo - $latFrom;
    $lonDelta = $lonTo - $lonFrom;

    $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

    return $earthRadius * $angle;
}

}
