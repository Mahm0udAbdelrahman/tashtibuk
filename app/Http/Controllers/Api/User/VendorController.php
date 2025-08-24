<?php
namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\VendorResource;
use App\Http\Resources\Vendor\ProductResource;
use App\Http\Resources\Vendor\RegisterResource;
use App\Services\User\VendorService;
use App\Traits\HttpResponse;

class VendorController extends Controller
{
    use HttpResponse;
    public function __construct(public VendorService $vendorService)
    {}

    public function index()
    {
        $data = $this->vendorService->index();

        return $this->paginatedResponse($data, VendorResource::class);
    }

    public function show($id)
    {
        $data = $this->vendorService->show($id);

        return $this->okResponse(new RegisterResource($data));
    }

    public function productVendor($id)
    {
        $data = $this->vendorService->productVendor($id);

          return $this->paginatedResponse($data, ProductResource::class);

        // return $this->okResponse(new ProductResource($data));
    }

}
