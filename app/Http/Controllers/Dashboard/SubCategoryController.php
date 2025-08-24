<?php

namespace App\Http\Controllers\Dashboard;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\SubCategoryService;
use App\Http\Resources\Dashboard\SubCategoryResource;
use App\Http\Requests\Dashboard\SubCategory\StoreSubCategoryRequest;
use App\Http\Requests\Dashboard\SubCategory\UpdateSubCategoryRequest;

class SubCategoryController extends Controller
{
    use HttpResponse;
    public function __construct(public SubCategoryService $subCategoryService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->subCategoryService->index();
        return $this->paginatedResponse($data ,SubCategoryResource::class);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubCategoryRequest $request)
    {
        $data = $this->subCategoryService->store($request->validated());
         return  $this->okResponse(new SubCategoryResource($data) , __('Create Sub Category', [], request()->header('Accept-language')) );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $data = $this->subCategoryService->show($id);
         return  $this->okResponse(new SubCategoryResource($data) , __('Show Sub Category', [], request()->header('Accept-language')) );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id,UpdateSubCategoryRequest $request)
    {
        $data = $this->subCategoryService->update($id,$request->validated());
         return  $this->okResponse(new SubCategoryResource($data) , __('Update Sub Category', [], request()->header('Accept-language')) );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->subCategoryService->delete($id);
        return  $this->okResponse('', __('Delete Sub Category', [], request()->header('Accept-language')) );

    }
}
