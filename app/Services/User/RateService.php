<?php
namespace App\Services\User;

use App\Models\Rate;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Auth;

class RateService
{
    use HttpResponse;

    public function __construct(public Rate $model)
    {}

    public function store($vendor_id, $data)
    {
        $user = Auth::user();

        $rate = $this->model->updateOrCreate(
            [
                'user_id'   => $user->id,
                'vendor_id' => $vendor_id,
            ],
            [
                'rate' => $data['rate'],
            ]
        );
        return $rate;
        // return $this->okResponse($rate, __('Rating submitted successfully'));
    }
}
