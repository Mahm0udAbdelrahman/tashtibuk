<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\CategoryService;
use App\Http\Resources\Dashboard\CategoryResource;
use App\Http\Requests\Dashboard\Category\StoreCategoryRequest;
use App\Http\Requests\Dashboard\Category\UpdateCategoryRequest;

class CategoryController extends Controller
{
    use HttpResponse;
    public function __construct(public CategoryService $categoryService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->categoryService->index();
        return $this->paginatedResponse($data ,CategoryResource::class);
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
    public function store(StoreCategoryRequest $request)
    {
        $data = $this->categoryService->store($request->validated());
         return  $this->okResponse(new CategoryResource($data) , __('Create Category', [], request()->header('Accept-language')) );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $data = $this->categoryService->show($id);
         return  $this->okResponse(new CategoryResource($data) , __('Show Category', [], request()->header('Accept-language')) );
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
    public function update($id,UpdateCategoryRequest $request)
    {
        $data = $this->categoryService->update($id,$request->validated());
         return  $this->okResponse(new CategoryResource($data) , __('Update Category', [], request()->header('Accept-language')) );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->categoryService->delete($id);
        return  $this->okResponse('', __('Delete Category', [], request()->header('Accept-language')) );

    }
}
