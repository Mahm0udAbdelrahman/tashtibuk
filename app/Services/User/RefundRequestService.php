<?php
namespace App\Services\User;

use App\Models\Order;
use App\Models\RefundRequest;
use App\Models\RefundRequestDetail;
use App\Exceptions\InsuranceNotFoundException;

class RefundRequestService
{
    public function __construct(public Order $model)
    {}

    public function show($id)
    {
        return $this->model->with('items')->findOrFail($id);
    }

    public function store($data)
    {
        $data['user_id'] = auth()->user()->id;

       $order = $this->model->findOrFail($data['order_id']);
       
       $order_refund_request = RefundRequest::where('order_id', $data['order_id'])
            ->where('status', 'pending')
            ->where('user_id', auth()->user()->id)
            ->first();
        if($order_refund_request) {
             throw new InsuranceNotFoundException(__('The request is pending', [], request()->header('Accept-language')), 400);
        }
       
       if($order->created_at->diffInDays(now()) > 14) {
         throw new InsuranceNotFoundException(__('Refund request can only be made within 14 days of order creation.', [], request()->header('Accept-language')), 400);
       }
      
       $refund_request  = RefundRequest::create($data);
 

        foreach ($data['details'] as $variant) {
            RefundRequestDetail::create([
                'refund_request_id' => $refund_request->id,
                'item_id'           => $variant['item_id'],
                'product_id'        => $variant['product_id'],
                'quantity'          => $variant['quantity'],
            ]);
        }

        return $refund_request->load('details');
    }

}
