<?php
namespace App\Http\Controllers\Api\User;

use App\Helpers\SendNotificationHelper;
use App\Http\Controllers\Api\User\FirebaseRDBController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\Order\OrderRequest;
use App\Http\Resources\User\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\Setting;
use App\Models\Vendor;
use App\Notifications\DBFireBaseNotification;
use App\Services\User\OrderService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    use HttpResponse;
    public function __construct(public OrderService $orderService)
    {}

    public function index(Request $request)
    {
        $data = $this->orderService->index($request->only('status'));

        return $this->paginatedResponse($data, OrderResource::class);
    }

    public function store(OrderRequest $orderRequest)
    {

        $method = $orderRequest->payment_method == 'cash' ? 'cashOrder' : 'store';
        return $this->orderService->$method($orderRequest->validated());

    }

    public function callback(Request $request)
    {
        $data = $request->all();

        if (! $data) {
            abort(400, 'Invalid data');
        }

        $order = Order::where('payment_id', $data['order'])->first();

        if (! $order) {
            abort(404, 'Order not found');
        }

        $success = filter_var($data['success'], FILTER_VALIDATE_BOOLEAN);

        if ($success) {
            if ($order->payment_status === 'paid') {
                $url = 'https://tashtibuk.rab7e.com/api/callback/?id=' . $data['id'] . '&is_true=' . $data['success'];
                return view('payment', compact('order', 'url'));
            }

            $balance = $data['amount_cents'] / 100;
            $order->update([
                'payment_status' => 'paid',
            ]);

            $vendorId       = $order->items->first()->product->vendor->id;
            $vendorDelivery = $order->items->first()->product->vendor->is_delivery;
            $vendor         = Vendor::findOrFail($vendorId);

            $orderTotal        = $order->total;
            $orderCostDelivery = $order->cost_delivery;
            $percentage_value  = Setting::value('percentage');

            $percentageAmount = ($orderTotal * $percentage_value) / 100;

            if ($vendorDelivery == 1) {
                $vendor->balance = ($vendor->balance ?? 0) + (($orderTotal - $percentageAmount) + $orderCostDelivery);

                $order->update([
                    'price_before_percentage' => $orderTotal,
                    'price_after_percentage'  => $orderTotal - $percentageAmount,
                    'total'                   => ($orderTotal - $percentageAmount) + $orderCostDelivery,
                    'order_balance'           => (($orderTotal - $percentageAmount) + $orderCostDelivery),
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

            $db        = new FirebaseRDBController("https://tashteebak-8a297-default-rtdb.firebaseio.com");
            $firstItem = $order->items()->first();
            $insert    = $db->update("orders", $order->id, [
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

            $order->load('items.product');
            foreach ($order->items as $item) {
                if ($item->product) {
                    $colorSize = $item->product->colorSizes()
                        ->where('color', $item->color)
                        ->where('size_id', $item->size_id)
                        ->first();
                    if ($colorSize) {
                        $colorSize->decrement('quantity', $item->quantity);
                    }
                }
            }

            $notificationData = [
                "title_ar" => "طلب جديد من " . $order->user->name,
                "body_ar"  => "لديك طلب جديد في متجرك، يرجى مراجعته ومعالجته في أقرب وقت.",
                "title_en" => "New Order Received from " . $order->user->name,
                "body_en"  => "You have received a new order in your store. Please review and process it as soon as possible.",
                'image'    => null,
            ];

            $firstItem = $order->items->first();
            if ($firstItem && $firstItem->product) {
                $vendor = Vendor::findOrFail($firstItem->product->vendor_id);

                Notification::send(
                    $vendor,
                    new DBFireBaseNotification(
                        $notificationData['title_ar'],
                        $notificationData['body_ar'],
                        $notificationData['title_en'],
                        $notificationData['body_en'],
                        $order->id,
                        $order->user->name,
                        $order->price
                    )
                );

                (new SendNotificationHelper())->sendNotification($notificationData, [$vendor->fcm_token]);
            }

            Cart::where('user_id', $order->user_id)->delete();
        } else {
            $order->update([
                'payment_status' => 'faild',
            ]);
        }

        $url = 'https://tashtibuk.rab7e.com/api/callback/?id=' . $data['id'] . '&is_true=' . $data['success'];
        return view('payment', compact('order', 'url'));
    }

}
