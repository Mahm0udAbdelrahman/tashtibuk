<?php

namespace App\Http\Controllers\Api\User;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\User\CategoryService;
use App\Http\Resources\User\CategoryResource;

class CategoryController extends Controller
{
    use HttpResponse;
    public function __construct(public CategoryService $categoryService){}

    public function index()
    {
      $data = $this->categoryService->index();

      return $this->paginatedResponse($data,CategoryResource::class);
    }
}
