<?php
namespace App\Http\Controllers\Api\User;

 use App\Traits\HttpResponse;
use App\Services\User\HomeService;
use App\Http\Resources\User\BestSellingProductResource;
 use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    use HttpResponse;

    public function __construct(public HomeService $homeService){}

    public function products()
    {
        $products = $this->homeService->products();
        return $this->paginatedResponse($products, BestSellingProductResource::class);

    }
}
