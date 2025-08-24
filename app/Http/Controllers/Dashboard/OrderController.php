<?php

namespace App\Http\Controllers\Dashboard;

use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\OrderService;
use App\Http\Resources\Dashboard\OrderResource;
use App\Http\Resources\Dashboard\DetailsOrderResource;


class OrderController extends Controller
{
    use HttpResponse;
    public function __construct(public OrderService $orderService){}

   public function index()
    {
        $data = $this->orderService->index();
        return $this->paginatedResponse($data, OrderResource::class);
    }

    public function show(string $id)
    {
        $data = $this->orderService->show($id);
        return $this->okResponse(new DetailsOrderResource($data), __('Show Order', [], request()->header('Accept-language')));
    }

    public function destroy(string $id)
    {
        $this->orderService->destroy($id);
        return $this->okResponse(null, __('Delete Order', [], request()->header('Accept-language')));
    }




}
