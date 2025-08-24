<?php
namespace App\Services\Vendor;

use App\Helpers\SendNotificationHelper;
use App\Http\Controllers\Api\User\FirebaseRDBController;
use App\Models\Delivery;
use App\Models\Order;
use App\Models\Setting;
use App\Models\OrderDelivery;
use App\Notifications\DBFireBaseNotification;
use Illuminate\Support\Facades\Notification;

class OrderService
{
    public function __construct(public Order $order)
    {}
    public function order()
    {
        $status = request('status');

        return $this->order->whereHas('items', function ($query) {
            $query->whereHas('product', function ($q) {
                $q->where('vendor_id', auth('vendor')->id());
            });
        })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->with(['items.product'])
            ->latest()
            ->paginate();
    }

    public function show(string $id)
    {

        return $this->order->with(['user', 'items' => function ($query) {
            $query->whereHas('product', function ($q) {
                $q->where('vendor_id', auth('vendor')->id());
            })->with('product');
        }])->findOrFail($id);
    }

    public function updateStatus($id, $data)
    {
        $order = $this->order->findOrFail($id);

        $order->update($data);

        $data = [
            "title_ar" => 'تم تحضير الطلب - ' . $order->user->name,
            "body_ar"  => "لقد تم تحضير الطلب وهو جاهز للاستلام والتوصيل الآن.",

            "title_en" => 'Order Prepared - ' . $order->user->name,
            "body_en"  => "The order has been prepared and is ready for pickup and delivery.",

            'image'    => null,
        ];

        $newNotification = new SendNotificationHelper();
        $firstItem       = $order->items()->with('product.vendor')->first();

        if ($firstItem && $firstItem->product && $firstItem->product->vendor && $firstItem->product->vendor->is_delivery == 0) {
            $vendor = $firstItem->product->vendor;

            $vendorLat = $vendor->lat;
            $vendorLng = $vendor->lng;
            $vendorDelivery = $vendor->is_delivery == 1;

            if ($vendorDelivery && isset($order->status) && ($order->status === "preparation" || $order->status === 'تم التحضير')) {

                $db = new FirebaseRDBController("https://tashteebak-8a297-default-rtdb.firebaseio.com");

                $db->delete("orders", $order->id);

            }
            
            $delivery_distance = Setting::value('delivery_distance');
    
            $deliveries = Delivery::select('*')
                ->selectRaw("
            (6371 * acos(
                cos(radians(?)) *
                cos(radians(lat)) *
                cos(radians(lng) - radians(?)) +
                sin(radians(?)) *
                sin(radians(lat))
            )) AS distance
        ", [$vendorLat, $vendorLng, $vendorLat])
                ->having('distance', '<=', $delivery_distance)
                ->get();

                 
            foreach ($deliveries as $delivery) {

                if (isset($order->status) && ($order->status === "preparation" || $order->status === 'تم التحضير')) {
                 

                    $db = new FirebaseRDBController("https://tashteebak-8a297-default-rtdb.firebaseio.com");
                   $db->delete("orders", $order->id);


                    $firstItem = $order->items()->first();
                    
                        $time = time();
                     $key = $time . '_' . $order->id . '_' . $delivery->id;


                    $insert = $db->insert("order_deliveries", [
                        'id'            => $order->id,
                        'delivery_id'   => $delivery->id,
                        'vendor_id'     => $firstItem->product->vendor->id ?? null,
                        'count_product' => $order->items->count(),
                        'cost_delivery' => $order->cost_delivery,
                        'number_order'  => $order->id,
                        'address'       => $order->address,
                        'lat'           => $order->lat,
                        'lng'           => $order->lng,
                        'total'         => str($order->price_before_percentage + $order->cost_delivery),
                        'status'        => $order->status,
                        'time'         => $time,
                        'created_at'    => $order->created_at->format('Y-m-d H:i:s'),
                        'updated_at'    => now()->format('Y-m-d H:i:s'),
                        
                        'is_delivery'   => OrderDelivery::where('order_id', $order->id)->value('status') ?? "-1",
                        'vendor'        => [
                            'name'    => $firstItem->product->vendor->shop_name ?? null,
                            'phone'   => $firstItem->product->vendor->shop_phone ?? null,
                            'address' => $firstItem->product->vendor->address ?? null,
                            'lat'     => $firstItem->product->vendor->lat ?? null,
                            'lng'     => $firstItem->product->vendor->lng ?? null,
                        ],
                    ], $key);
                }

                Notification::send(
                    $delivery,
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

                if ($delivery->fcm_token) {
                    $newNotification->sendNotification($data, [$delivery->fcm_token]);
                }
            }

        }

        return $order;
    }

}
