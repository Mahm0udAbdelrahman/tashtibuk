<?php
namespace App\Http\Controllers\Api\User;

use App\Models\Setting;
use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\HelpService;
use App\Http\Resources\Dashboard\HelpResource;

class HelpController extends Controller
{
    use HttpResponse;
    public function __construct(public HelpService $HelpService)
    {}

    public function index()
    {
        $data     = $this->HelpService->index();
        $response = [
            'description_setting' => Setting::first()->{'description_' . app()->getLocale()},
            'phone'               => Setting::first()->phone,
            'email'               => Setting::first()->email,
        ];
        return $this->successResponse(HelpResource::collection($data),'sddsadsa',200,$response);
    }

}
