<?php

namespace App\Http\Controllers\Dashboard;

 use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\DeliveryService;
use App\Http\Resources\Dashboard\DeliveryResource;
use App\Http\Requests\Dashboard\Delivery\StoreDeliveryRequest;
use App\Http\Requests\Dashboard\Delivery\UpdateDeliveryRequest;
use App\Http\Resources\Dashboard\DetailsDeliveryResource;

class DeliveryController extends Controller
{
    use HttpResponse;
    public function __construct(public DeliveryService $deliveryService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->deliveryService->index();
        return $this->paginatedResponse($data ,DeliveryResource::class);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDeliveryRequest $request)
    {
        $data = $this->deliveryService->store($request->validated());
         return  $this->okResponse(new DeliveryResource($data) , __('Create Delivery', [], request()->header('Accept-language')) );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $data = $this->deliveryService->show($id);
         return  $this->okResponse(new DetailsDeliveryResource($data) , __('Show Delivery', [], request()->header('Accept-language')) );
    }



    /**
     * Update the specified resource in storage.
     */
    public function update($id,UpdateDeliveryRequest $request)
    {
        $data = $this->deliveryService->update($id,$request->validated());
         return  $this->okResponse(new DeliveryResource($data) , __('Update Delivery', [], request()->header('Accept-language')) );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->deliveryService->delete($id);
        return  $this->okResponse('', __('Delete Delivery', [], request()->header('Accept-language')) );

    }
}
