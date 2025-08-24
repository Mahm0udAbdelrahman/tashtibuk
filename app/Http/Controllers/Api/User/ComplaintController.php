<?php
namespace App\Http\Controllers\Api\User;

use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\User\ComplaintService;
use App\Http\Resources\User\ComplaintResource;
use App\Http\Requests\Api\User\Complaint\ComplaintRequest;

class ComplaintController extends Controller
{
    use HttpResponse;
    public function __construct(public ComplaintService $complaintService)
    {}

    public function store(ComplaintRequest $request)
    {
        $data = $this->complaintService->store($request->validated());
        return $this->okResponse(new ComplaintResource($data), __('Complaint created successfully', [], request()->header('Accept-language')));
    }

}
