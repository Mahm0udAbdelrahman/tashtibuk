<?php
namespace App\Services\Dashboard;

use App\Models\Delivery;
use App\Models\Withdrawal;
use App\Traits\HttpResponse;

class WithdrawalDeliveryService
{
    use HttpResponse;

    public function __construct(public Withdrawal $withdrawal)
    {}

    public function index()
    {
        return $this->withdrawal->where('delivery_id' ,'!=' , null)->latest()->paginate();
    }

     public function update($id, $data)
    {
        $withdrawal = $this->withdrawal->findOrFail($id);
        if (isset($data['status']) && $data['status'] == 'accept') {
            $delivery = Delivery::find($withdrawal->delivery_id);
            if ($delivery) {
                $delivery->balance -= $withdrawal->withdrawal;
                $delivery->save();
            }
        }
        $withdrawal->update($data);
        return $withdrawal;
    }

    public function destroy($id)
    {
        $withdrawal = $this->withdrawal->findOrFail($id);
        $withdrawal->delete();
        return $this->okResponse([], __('Withdrawal request deleted successfully'));
    }

}
