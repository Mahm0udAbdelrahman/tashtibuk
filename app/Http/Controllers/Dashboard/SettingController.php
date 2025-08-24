<?php

namespace App\Http\Controllers\Dashboard;

use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\SettingService;
use App\Http\Resources\Dashboard\SettingResource;
use App\Http\Requests\Dashboard\Setting\SettingRequest;


class SettingController extends Controller
{
    use HttpResponse;
    public function __construct(public SettingService $SettingService){}
    
    public function index()
    {
        $data = $this->SettingService->index();
        return $this->okResponse(new SettingResource($data), __('Show Setting', [], request()->header('Accept-language')));
    }

    public function update(SettingRequest $request)
    {
        $data = $this->SettingService->update($request->validated());
         return  $this->okResponse(new SettingResource($data) , __('Create Setting', [], request()->header('Accept-language')) );
    }


}
