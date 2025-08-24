<?php

namespace App\Http\Controllers\Dashboard;

use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\TermsOfConditionsService;
use App\Http\Resources\User\TermsOfConditionsResource;
use App\Http\Requests\Dashboard\TermsOfConditions\TermsOfConditionsRequest;


class TermsOfConditionsController extends Controller
{
    use HttpResponse;
    public function __construct(public TermsOfConditionsService $termsOfConditionsService){}

    public function update(TermsOfConditionsRequest $request)
    {
        $data = $this->termsOfConditionsService->update($request->validated());
         return  $this->okResponse(new TermsOfConditionsResource($data) , __('Create TermsOfConditions', [], request()->header('Accept-language')) );
    }


}
