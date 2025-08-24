<?php
namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Vendor\Product\StoreProductRequest;
use App\Http\Requests\Api\Vendor\Product\UpdateProductRequest;
use App\Http\Resources\Dashboard\SizeResource;
use App\Http\Resources\Dashboard\SubCategoryResource;
use App\Http\Resources\User\CategoryResource;
use App\Http\Resources\Vendor\DetailsProductResource;
use App\Http\Resources\Vendor\ProductResource;
use App\Services\Vendor\ProductService;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HttpResponse;

    public function __construct(public ProductService $productService)
    {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->productService->index($request->only(['category_id', 'name','sub_category_id','sub_category_name']));
        return $this->paginatedResponse($data, ProductResource::class);
    }

    public function store(StoreProductRequest $request)
    {
        $data = $this->productService->store($request->validated());
        return $this->okResponse(new DetailsProductResource($data));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->productService->show($id);
        return $this->okResponse(new DetailsProductResource($data));
    }

    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $data = $this->productService->update($id, $request->validated());
        return $this->okResponse(new DetailsProductResource($data));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->productService->destroy($id);
        return $this->okResponse('', 'Deleted Product');
    }

    public function size()
    {
        $data = $this->productService->size();
        return $this->simpleResponse($data, SizeResource::class);
    }

    public function category()
    {
        $data = $this->productService->category();
        return $this->simpleResponse($data, CategoryResource::class);
    }

    public function subCategory()
    {
        $data = $this->productService->subCategory();
        return $this->simpleResponse($data, SubCategoryResource::class);
    }

}
