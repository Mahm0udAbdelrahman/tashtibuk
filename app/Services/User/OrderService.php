<?php
namespace App\Services\User;

use App\Helpers\SendNotificationHelper;
use App\Http\Controllers\Api\User\FirebaseRDBController;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\Setting;
use App\Models\Vendor;
use App\Notifications\DBFireBaseNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

class OrderService
{
    protected $baseUrl;
    protected $secretKey;
    protected $publicKey;
    protected $cardId;
    protected $api_key;
    protected $walletId;
    protected $walletIdFrame;
    protected $cardIdFrame;

    public function __construct(public CartService $cartService)
    {
        $this->baseUrl       = env('PAYMOB_API_URL');
        $this->secretKey     = env('PAYMOB_SECRET_KEY');
        $this->publicKey     = env('PAYMOB_PUBLIC_KEY');
        $this->cardId        = env('PAYMOB_INTEGRATION_ID');
        $this->walletId      = env('PAYMOB_WALLET_INTEGRATION_ID');
        $this->api_key       = env('PAYMOB_API_KEY');
        $this->cardIdFrame   = env('PAYMOB_IFRAME_ID');
        $this->walletIdFrame = env('PAYMOB_WALLET_IFRAME_ID');
    }

    public function index($request)
    {
        $userId = auth('sanctum')->user()->id;

        $query = Order::where('user_id', $userId)->with(['items.product']);

        if (! empty($request['status'])) {
            $query->where('status', $request['status']);
        }

        return $query->latest()->paginate();
    }

    public function cashOrder($data)
    {
        $user = auth()->user();

        $cartItems = Cart::where('user_id', $user->id)->get();
        $db        = new FirebaseRDBController("https://tashteebak-8a297-default-rtdb.firebaseio.com");

        $order = Order::create([
            'user_id'          => $user->id,
            'cost_delivery'    => $this->cartService->costDelivery(),
            'total'            => $cartItems->sum('total'),
            'name'             => $data['name'],
            'address'          => $data['address'],
            'lng'              => $data['lng'],
            'lat'              => $data['lat'],
            'building_number'  => $data['building_number'],
            'floor_number'     => $data['floor_number'],
            'apartment_number' => $data['apartment_number'],
            'number_product'   => $cartItems->count(),
            'payment_method'   => 'cash',
            'payment_id'       => null,
            'phone'            => $data['phone'],
            'payment_status'   => 'pending',
        ]);

        foreach ($cartItems as $item) {
            $order->items()->updateOrCreate(
                ['order_id' => $order->id, 'product_id' => $item->product_id, 'size_id' => $item->size_id, 'color' => $item->color],
                [
                    'quantity' => $item->quantity,
                    'price'    => $item->price,
                    'total'    => $item->price * $item->quantity,
                ]
            );

            if ($item->product) {

                $colorSize = $item->product->colorSizes()
                    ->where('color', $item->color)
                    ->where('size_id', $item->size_id)
                    ->first();

                if ($colorSize) {
                    if ($colorSize->quantity >= $item->quantity) {
                        $colorSize->decrement('quantity', $item->quantity);
                    } else {
                        throw new \Exception(__('الكمية المطلوبة غير متوفرة للمقاس واللون المحددين.'));
                    }
                }
            }
        }

        $vendorId       = $order->items->first()->product->vendor->id;
        $vendorDelivery = $order->items->first()->product->vendor->is_delivery;
        $vendor         = Vendor::findOrFail($vendorId);

        $orderTotal        = $order->total;
        $orderCostDelivery = $order->cost_delivery;
        $percentage_value  = Setting::value('percentage');

        $percentageAmount = ($orderTotal * $percentage_value) / 100;

        if ($vendorDelivery == 1) {
            $vendor->balance = ($vendor->balance ?? 0) - $percentageAmount;

            $order->update([
                'price_before_percentage' => $orderTotal,
                'price_after_percentage'  => $orderTotal - $percentageAmount,
                'total'                   => ($orderTotal - $percentageAmount) + $orderCostDelivery,
                'order_balance'           =>  - $percentageAmount,
                'type'  => 'vendor'
            ]);

        } else {

            $vendor->balance = ($vendor->balance ?? 0) + ($orderTotal - $percentageAmount);

            $order->update([
                'price_before_percentage' => $orderTotal,
                'price_after_percentage'  => $orderTotal - $percentageAmount,
                'total'                   => $orderTotal + $orderCostDelivery,
                'order_balance'           => ($orderTotal - $percentageAmount),
            ]);
         
        }
        $vendor->save();

        $setting          = Setting::first();
        $setting->balance = $setting->balance + $percentageAmount;
        $setting->save();

        $firstItem = $order->items()->first();
        $db->update("orders", $order->id, [
            'id'            => $order->id,
            'vendor_id'     => $firstItem->product->vendor->id ?? null,
            'count_product' => $order->items->count(),
            'cost_delivery' => $order->cost_delivery,
            'number_order'  => $order->id,
            'address'       => $order->address,
            'lat'           => $order->lat,
            'lng'           => $order->lng,
            'total'         => str($order->price_before_percentage + $order->cost_delivery),

            'status'        => $order->status,
            'created_at'    => $order->created_at->format('Y-m-d H:i:s'),
            'is_delivery'   => OrderDelivery::where('order_id', $order->id)->value('status') ?? "-1",
            'vendor'        => [
                'name'    => $firstItem->product->vendor->shop_name ?? null,
                'phone'   => $firstItem->product->vendor->shop_phone ?? null,
                'address' => $firstItem->product->vendor->address ?? null,
                'lat'     => $firstItem->product->vendor->lat ?? null,
                'lng'     => $firstItem->product->vendor->lng ?? null,
            ],
        ]);

        Cart::where('user_id', $user->id)->delete();

        Session::flash('message', ['type' => 'success', 'text' => __('تم إنشاء الطلب بنجاح')]);
        $data = [
            "title_ar" => "طلب جديد من " . $order->user->name,
            "body_ar"  => "لديك طلب جديد في متجرك، يرجى مراجعته ومعالجته في أقرب وقت.",

            "title_en" => "New Order Received from " . $order->user->name,
            "body_en"  => "You have received a new order in your store. Please review and process it as soon as possible.",

            'image'    => null,
        ];

        $newNotification = new SendNotificationHelper();
        $firstItem       = $order->items()->with('product')->first();

        if ($firstItem && $firstItem->product) {
            $vendor = Vendor::findOrFail($firstItem->product->vendor_id);

            Notification::send(
                $vendor,
                new DBFireBaseNotification($data['title_ar'], $data['body_ar'], $data['title_en'], $data['body_en'], $order->id, $order->user->name, $order->price)
            );

            $newNotification->sendNotification($data, [$vendor->fcm_token]);
        }
        return $order;
    }

    public function store($data)
    {

        $user = auth()->user();

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'المستخدم غير موجود'], 401);
        }

        $authResponse = Http::post("https://accept.paymob.com/api/auth/tokens", [
            'api_key' => $this->api_key,
        ]);

        if (! $authResponse->successful()) {
            return response()->json([
                'status'  => false,
                'message' => __('فشل في المصادقة مع Paymob'),
            ], 404);
        }

        $cartItems = Cart::where('user_id', $user->id)->get();

        $authToken = $authResponse->json()['token'];

        $orderResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $authToken,
            'Content-Type'  => 'application/json',
        ])->post("https://accept.paymob.com/api/ecommerce/orders", [
            'auth_token'      => $authToken,
            'delivery_needed' => false,
            'amount_cents'    => ($cartItems->sum('total') + $this->cartService->costDelivery()) * 100,
            'currency'        => 'EGP',
            'items'           => [],
        ]);

        if (! $orderResponse->successful()) {
            return response()->json([
                'status'  => false,
                'message' => __('فشل في إنشاء الطلب في Paymob'),
            ], 404);
        }

        $paymobOrderId = $orderResponse->json()['id'];

        $order = Order::create(
            [
                'user_id'          => auth()->user()->id,
                'cost_delivery'    => $this->cartService->costDelivery(),
                'total'            => $cartItems->sum('total'),
                'name'             => $data['name'],
                'address'          => $data['address'],
                'payment_method'   => 'card',
                'payment_id'       => $paymobOrderId,
                'phone'            => $data['phone'],
                'lng'              => $data['lng'],
                'lat'              => $data['lat'],
                'building_number'  => $data['building_number'],
                'floor_number'     => $data['floor_number'],
                'apartment_number' => $data['apartment_number'],
                'number_product'   => $cartItems->count(),
                'payment_status'   => 'pending',
            ]);
        $amount = $cartItems->sum('total') + $this->cartService->costDelivery();

        $billing = [
            "apartment"       => "123",
            "first_name"      => $user->name,
            "last_name"       => "غير محدد",
            "street"          => 'لا يوجد عنوان',
            "building"        => "456",
            "phone_number"    => $user->phone,
            "city"            => 'غير محدد',
            "country"         => "EG",
            "email"           => $user->email,
            "floor"           => "1",
            "state"           => 'غير محدد',
            "postal_code"     => "12345",
            "shipping_method" => "PKG",
        ];

        $integrationId = request()->input('payment_method') == 'card' ? $this->cardId : $this->walletId;

        $paymentKeyResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $authToken,
            'Content-Type'  => 'application/json',
        ])->post("https://accept.paymob.com/api/acceptance/payment_keys", [
            'auth_token'     => $authToken,
            'amount_cents'   => $amount * 100,
            'expiration'     => 3600,
            'order_id'       => $paymobOrderId,
            'billing_data'   => $billing,
            'currency'       => 'EGP',
            'integration_id' => $integrationId,
        ]);

        foreach ($cartItems as $item) {
            $order->items()->updateOrCreate(
                ['order_id' => $order->id, 'product_id' => $item->product_id, 'size_id' => $item->size_id, 'color' => $item->color],
                [
                    'quantity' => $item->quantity,
                    'price'    => $item->price,
                    'total'    => $item->price * $item->quantity,
                ]
            );

        }

        if (! $paymentKeyResponse->successful()) {
            Log::error('Paymob Payment Key Error:', $paymentKeyResponse->json());
            return response()->json([
                'status'  => false,
                'message' => __('فشل في إنشاء مفتاح الدفع'),
            ], 404);
        }

        $paymentKey = $paymentKeyResponse->json()['token'];

        $iframeId = request()->input('payment_method') == 'card' ? env('PAYMOB_IFRAME_ID') : env('PAYMOB_WALLET_IFRAME_ID');

        return [
            'payment_id'   => $paymobOrderId,
            'price'        => $amount,
            'redirect_url' => "https://accept.paymob.com/api/acceptance/iframes/{$iframeId}?payment_token={$paymentKey}&amount={$amount}",
        ];
    }

}
