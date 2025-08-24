<?php

namespace App\Http\Controllers\Api\Delivery;

use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\Delivery\NotificationService;
use App\Http\Resources\Vendor\NotificationResouce;


class NotificationController extends Controller
{
    use   HttpResponse;
    public function __construct(public NotificationService $notificationService){}


    public function index()
    {
        $limit = request()->get('limit', 10);
        $data = $this->notificationService->index($limit);
        return $this->paginatedResponse($data, NotificationResouce::class);
    }
}
