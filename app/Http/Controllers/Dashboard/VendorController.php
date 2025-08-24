<?php

namespace App\Http\Controllers\Dashboard;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\VendorService;
use App\Http\Resources\Dashboard\VendorResource;
use App\Http\Requests\Dashboard\Vendor\StoreVendorRequest;
use App\Http\Requests\Dashboard\Vendor\UpdateVendorRequest;
use App\Http\Resources\Dashboard\DetailsVendorResource;

class VendorController extends Controller
{
    use HttpResponse;
    public function __construct(public VendorService $vendorService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->vendorService->index();
        return $this->paginatedResponse($data ,VendorResource::class);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVendorRequest $request)
    {
        $data = $this->vendorService->store($request->validated());
         return  $this->okResponse(new VendorResource($data) , __('Create Vendor', [], request()->header('Accept-language')) );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $data = $this->vendorService->show($id);
         return  $this->okResponse(new DetailsVendorResource($data) , __('Show Vendor', [], request()->header('Accept-language')) );
    }



    /**
     * Update the specified resource in storage.
     */
    public function update($id,UpdateVendorRequest $request)
    {
        $data = $this->vendorService->update($id,$request->validated());
         return  $this->okResponse(new VendorResource($data) , __('Update Vendor', [], request()->header('Accept-language')) );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->vendorService->delete($id);
        return  $this->okResponse('', __('Delete Vendor', [], request()->header('Accept-language')) );

    }
}
