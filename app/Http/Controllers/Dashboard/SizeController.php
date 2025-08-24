<?php

namespace App\Http\Controllers\Dashboard;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\SizeService;
use App\Http\Resources\Dashboard\SizeResource;
use App\Http\Requests\Dashboard\Size\StoreSizeRequest;
use App\Http\Requests\Dashboard\Size\UpdateSizeRequest;

class SizeController extends Controller
{
    use HttpResponse;
    public function __construct(public SizeService $sizeService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->sizeService->index();
        return $this->paginatedResponse($data ,SizeResource::class);
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
    public function store(StoreSizeRequest $request)
    {
        $data = $this->sizeService->store($request->validated());
         return  $this->okResponse(new SizeResource($data) , __('Create Size', [], request()->header('Accept-language')) );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $data = $this->sizeService->show($id);
         return  $this->okResponse(new SizeResource($data) , __('Show Size', [], request()->header('Accept-language')) );
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
    public function update($id,UpdateSizeRequest $request)
    {
        $data = $this->sizeService->update($id,$request->validated());
         return  $this->okResponse(new SizeResource($data) , __('Update Size', [], request()->header('Accept-language')) );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->sizeService->delete($id);
        return  $this->okResponse('', __('Delete Size', [], request()->header('Accept-language')) );

    }
}
