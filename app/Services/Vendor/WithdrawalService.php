<?php
namespace App\Services\Vendor;


use App\Models\Withdrawal;
use App\Traits\HttpResponse;

class WithdrawalService
{
    use HttpResponse;

    public function __construct(public Withdrawal $withdrawal)
    {}

    public function store()
    {
        $vendor = auth('vendor')->user();
      
        if($vendor->balance == 0)
        {
            return $this->errorResponse([], 422,_('You have no profits now'));
        }
        $pendingWithdrawal = $this->withdrawal
            ->where('vendor_id', $vendor->id)
            ->where('status', 'pending')
            ->first();
        if ($pendingWithdrawal) {

            return $this->errorResponse([], 422,__('A settlement request has already been sent'));
        }

        $this->withdrawal->create([
            'vendor_id'  => $vendor->id,
            'withdrawal' => $vendor->balance,
            'status'     => 'pending',
        ]);

        return $this->okResponse([], __('Withdrawal request submitted successfully'));
    }
}
