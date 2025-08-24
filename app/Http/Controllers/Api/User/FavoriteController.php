<?php

namespace App\Http\Controllers\Api\User;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\User\CategoryService;
use App\Services\User\FavoriteService;
use App\Http\Resources\User\CategoryResource;
use App\Http\Resources\User\FavoriteResource;

class FavoriteController extends Controller
{
    use HttpResponse;
    public function __construct(public FavoriteService $favoriteService){}

    public function index()
    {
      $data = $this->favoriteService->index();

      return $this->paginatedResponse($data,FavoriteResource::class);
    }

    public function store($product_id)
    {
      return $this->favoriteService->store($product_id);
    }
}
