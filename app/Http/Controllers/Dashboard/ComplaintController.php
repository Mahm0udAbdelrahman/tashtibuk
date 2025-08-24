<?php
namespace App\Http\Controllers\Dashboard;

use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\ComplaintService;
use App\Http\Resources\User\ComplaintResource;
use App\Http\Requests\Api\User\Complaint\ComplaintRequest;

class ComplaintController extends Controller
{
    use HttpResponse;
    public function __construct(public ComplaintService $complaintService)
    {}


    public function index()
    {
        $data = $this->complaintService->index();
        return $this->paginatedResponse($data, ComplaintResource::class);
    }

    public function show(string $id)
    {
        $data = $this->complaintService->show($id);
        return $this->okResponse(new ComplaintResource($data), __('Show Complaint', [], request()->header('Accept-language')));
    }

    public function destory(string $id)
    {
        $this->complaintService->destory($id);
        return $this->okResponse([], __('Complaint deleted successfully', [], request()->header('Accept-language')));
    }


}
