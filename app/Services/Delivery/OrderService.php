<?php
namespace App\Services\Delivery;

use App\Exceptions\InsuranceNotFoundException;
use App\Helpers\SendNotificationHelper;
use App\Http\Controllers\Api\User\FirebaseRDBController;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\Setting;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\DBFireBaseNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
class OrderService
{
    public function __construct(public Order $order)
    {}

    public function order()
    {
        $delivery          = auth('delivery')->user();
        $status            = request('status');
        $deliveryLat       = $delivery->lat;
        $deliveryLng       = $delivery->lng;
        $delivery_distance = Setting::value('delivery_distance') ?? 10;

        $acceptedOrders = OrderDelivery::where('delivery_id', $delivery->id)
            ->where('status', '1')
            ->pluck('order_id');

        $rejectedOrders = OrderDelivery::where('delivery_id', $delivery->id)
            ->where('status', '0')
            ->pluck('order_id');

        if ($delivery->is_active == 0) {
            return $this->order
                ->whereIn('orders.id', $acceptedOrders)
                ->when($status, function ($query) use ($status) {
                    $query->where('orders.status', $status);
                })
                ->with(['items.product.vendor'])
                ->groupBy('orders.id')
                ->orderBy('orders.created_at', 'desc')
                ->paginate();
        }

        if ($delivery->status != 1) {
            return $this->order->whereRaw('1 = 0')->paginate();
        }

        if ($this->order->id && in_array($this->order->id, $acceptedOrders->toArray())) {
            return $this->order
                ->where('id', $this->order->id)
                ->with(['items.product.vendor'])
                ->paginate();
        }

        return $this->order
            ->selectRaw('orders.*,
        ROUND(
            MIN(
                6371 * acos(
                    cos(radians(?)) * cos(radians(vendors.lat)) *
                    cos(radians(vendors.lng) - radians(?)) +
                    sin(radians(?)) * sin(radians(vendors.lat))
                )
            ), 2
        ) AS distance', [$deliveryLat, $deliveryLng, $deliveryLat])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('vendors', 'products.vendor_id', '=', 'vendors.id')
            ->whereNotIn('orders.id', $rejectedOrders)
            ->where('vendors.is_delivery', '!=', '1')
            ->where(function ($query) use ($acceptedOrders, $deliveryLat, $deliveryLng, $delivery_distance) {
                $query->whereIn('orders.id', $acceptedOrders)
                    ->orWhere(function ($q) use ($deliveryLat, $deliveryLng , $delivery_distance) {
                        $q->whereNotExists(function ($subQuery) {
                            $subQuery->select(DB::raw(1))
                                ->from('order_deliveries')
                                ->whereColumn('order_deliveries.order_id', 'orders.id')
                                ->where('order_deliveries.status', '1');
                        })
                            ->whereRaw("
                    (6371 * acos(
                        cos(radians(?)) * cos(radians(vendors.lat)) *
                        cos(radians(vendors.lng) - radians(?)) +
                        sin(radians(?)) * sin(radians(vendors.lat))
                    )) <= ?
                ", [$deliveryLat, $deliveryLng, $deliveryLat , $delivery_distance]);
                    });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('orders.status', $status);
            })
            ->with(['items.product.vendor'])
            ->groupBy('orders.id')
            ->orderBy('orders.created_at', 'desc')
            ->paginate();

    }

    public function show(string $id)
    {
        return $this->order->with(['user', 'items.product'])->findOrFail($id);
    }

    public function updateStatus($id, $data)
    {
        $order = $this->order->findOrFail($id);
        $order->update($data);

        $delivery       = auth('delivery')->user();
        $order_delivery = OrderDelivery::where('order_id', $order->id)->where('delivery_id', $delivery->id)->first();

        if ($order->status == 'delivery' || $order->status == 'جاري التوصيل') {

            if ($order->payment_method == 'cash') {
                $order_delivery->update([
                    'order_value' => -$order->price_before_percentage,
                ]);
            } elseif ($order->payment_method == 'card') {
                $order_delivery->update([
                    'order_value' => $order->cost_delivery,
                ]);
            }

            $delivery = Delivery::find($order_delivery->delivery_id);

            if ($delivery) {
                $delivery->update([
                    'balance' => $delivery->balance + $order_delivery->order_value,
                ]);

            }

            $data = [
                "title_ar" => 'طلبك في الطريق - ' . $order->user->name,
                "body_ar"  => "تم استلام طلبك من المتجر وهو الآن في الطريق إليك. شكراً لثقتك بنا.",

                "title_en" => 'Your Order is on the Way - ' . $order->user->name,
                "body_en"  => "Your order has been picked up and is now on the way to you. Thank you for your trust.",

                'image'    => null,
            ];

            $newNotification = new SendNotificationHelper();
            $user            = User::findOrFail($order->user_id);
            Notification::send(
                $user,
                new DBFireBaseNotification(
                    $data['title_ar'],
                    $data['body_ar'],
                    $data['title_en'],
                    $data['body_en'],
                    $order->id,
                    $order->user->name,
                    $order->price
                )
            );
            $newNotification->sendNotification($data, [$user->fcm_token]);
        }

        if ($order->status == 'completed' || $order->status == 'تم التوصيل') {

            $data = [
                "title_ar" => 'تم التوصيل - ' . $order->user->name,
                "body_ar"  => "تم تسليم الطلب بنجاح. شكراً لك على تعاونك.",
                "title_en" => 'Delivered - ' . $order->user->name,
                "body_en"  => "Your order has been delivered successfully. Thank you for your cooperation.",
                'image'    => null,
            ];

            $newNotification = new SendNotificationHelper();
            $user            = User::findOrFail($order->user_id);
            Notification::send(
                $user,
                new DBFireBaseNotification(
                    $data['title_ar'],
                    $data['body_ar'],
                    $data['title_en'],
                    $data['body_en'],
                    $order->id,
                    $order->user->name,
                    $order->price
                )
            );
            $newNotification->sendNotification($data, [$user->fcm_token]);
        }

        return $order;
    }
    public function acceptOrder($id, $data)
    {
        $order = Order::find($id);

        if (! $order) {
            throw new InsuranceNotFoundException(__('Order not found', [], request()->header('Accept-language')), 400);

        }

        $delivery = auth('delivery')->user();

        $order_delivery = OrderDelivery::updateOrCreate(
            [
                'delivery_id' => $delivery->id,
                'order_id'    => $id,
            ],
            [
                'status' => $data['status'],
            ]
        );

        $orderTotal = $order->total;

        if ($order_delivery) {
            $deliveryWallet                  = Wallet::firstOrNew(['delivery_id' => $order_delivery->delivery_id]);
            $deliveryWallet->delivery_wallet = ($deliveryWallet->delivery_wallet ?? 0) - $orderTotal;

            $deliveryWallet->cost_delivery = ($deliveryWallet->cost_delivery ?? 0) + $order->cost_delivery;

            // $deliveryWallet->total = (($deliveryWallet->delivery_wallet ?? 0 + $deliveryWallet->cost_delivery)  + $deliveryWallet->cost_delivery);
            $deliveryWallet->total = -$deliveryWallet->delivery_wallet;

            $deliveryWallet->save();
        }

        if ($data['status'] == 1) {
            $db = new FirebaseRDBController("https://tashteebak-8a297-default-rtdb.firebaseio.com");

            $orderDeliveries = $db->retrieve("order_deliveries");
            $orderDeliveries = json_decode($orderDeliveries, true);

            if ($orderDeliveries) {
                foreach ($orderDeliveries as $key => $value) {
                    $parts = explode('_', $key);

                    if (
                        isset($parts[1], $parts[2]) &&
                        $parts[1] == $order->id &&
                        $parts[2] != $order_delivery->delivery_id
                    ) {
                        $db->delete("order_deliveries", $key);
                    }
                }
            }

        }

        return $order_delivery;
    }

}
