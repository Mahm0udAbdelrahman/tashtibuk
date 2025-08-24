<?php

namespace App\Http\Controllers\Dashboard;

use App\Traits\HttpResponse;
use App\Http\Controllers\Controller;
use App\Services\Dashboard\UserService;
use App\Http\Resources\Dashboard\UserResource;
use App\Http\Resources\Dashboard\DetailsUserResource;
use App\Http\Requests\Dashboard\User\StoreUserRequest;
use App\Http\Requests\Dashboard\User\UpdateUserRequest;

class UserController extends Controller
{
    use HttpResponse;
    public function __construct(public UserService $userService){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->userService->index();
        return $this->paginatedResponse($data ,UserResource::class);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $this->userService->store($request->validated());
         return  $this->okResponse(new UserResource($data) , __('Create User', [], request()->header('Accept-language')) );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $data = $this->userService->show($id);
         return  $this->okResponse(new DetailsUserResource($data) , __('Show User', [], request()->header('Accept-language')) );
    }



    /**
     * Update the specified resource in storage.
     */
    public function update($id,UpdateUserRequest $request)
    {
        $data = $this->userService->update($id,$request->validated());
         return  $this->okResponse(new UserResource($data) , __('Update User', [], request()->header('Accept-language')) );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userService->delete($id);
        return  $this->okResponse('', __('Delete User', [], request()->header('Accept-language')) );

    }
}
