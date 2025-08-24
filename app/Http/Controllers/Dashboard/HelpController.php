<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Help;
use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\HelpService;
use App\Http\Resources\Dashboard\HelpResource;
use App\Http\Requests\Dashboard\Help\StoreHelpRequest;
use App\Http\Requests\Dashboard\Help\UpdateHelpRequest;

class HelpController extends Controller
{
    use HttpResponse;
    public function __construct(public HelpService $HelpService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->HelpService->index();
        return $this->okResponse(HelpResource::collection($data), __('Help List ', [], request()->header('Accept-language')));

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
    public function store(StoreHelpRequest $request)
    {
        $data = $this->HelpService->store($request->validated());
         return  $this->okResponse(new HelpResource($data) , __('Create Help', [], request()->header('Accept-language')) );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $data = $this->HelpService->show($id);
         return  $this->okResponse(new HelpResource($data) , __('Show Help', [], request()->header('Accept-language')) );
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
    public function update($id,UpdateHelpRequest $request)
    {
        $data = $this->HelpService->update($id,$request->validated());
         return  $this->okResponse(new HelpResource($data) , __('Update Help', [], request()->header('Accept-language')) );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->HelpService->delete($id);
        return  $this->okResponse('', __('Delete Help', [], request()->header('Accept-language')) );

    }
}
