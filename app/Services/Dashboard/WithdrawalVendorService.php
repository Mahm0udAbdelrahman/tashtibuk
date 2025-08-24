<?php
namespace App\Services\Dashboard;

use App\Models\Vendor;
use App\Models\Wallet;
use App\Models\Withdrawal;
use App\Traits\HttpResponse;

class WithdrawalVendorService
{
    use HttpResponse;

    public function __construct(public Withdrawal $withdrawal)
    {}

    public function index()
    {
        return $this->withdrawal->where('vendor_id' ,'!=' , null)->latest()->paginate();
    }

    public function update($id, $data)
    {
        $withdrawal = $this->withdrawal->findOrFail($id);
        if (isset($data['status']) && $data['status'] == 'accept') {
            $vendor = Vendor::find($withdrawal->vendor_id);
            if ($vendor) {
                $vendor->balance -= $withdrawal->withdrawal;
                $vendor->save();
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
