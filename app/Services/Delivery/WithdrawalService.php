<?php
namespace App\Services\Delivery;

use App\Models\Withdrawal;
use App\Traits\HttpResponse;

class WithdrawalService
{
    use HttpResponse;

    public function __construct(public Withdrawal $withdrawal)
    {}

    public function store()
    {
        $delivery = auth('delivery')->user();
 
        if($delivery->balance == 0)
        {
            return $this->errorResponse([], 422,__('You have no profits now'));
        }

        $pendingWithdrawal = $this->withdrawal
            ->where('delivery_id', $delivery->id)
            ->where('status', 'pending')
            ->first();
        if ($pendingWithdrawal) 
        {
            return $this->errorResponse([], 422,__('A settlement request has already been sent'));
        }

        $this->withdrawal->create([
            'delivery_id'  => $delivery->id,
            'withdrawal' => $delivery->balance,
            'status'     => 'pending',
        ]);

        return $this->okResponse([], __('Withdrawal request submitted successfully'));
    }
}
