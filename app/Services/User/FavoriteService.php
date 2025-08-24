<?php
namespace App\Services\User;

use App\Models\Favorite;
use App\Traits\HttpResponse;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    use HttpResponse;
    public function __construct(public Favorite $model)
    {}

    public function index()
    {
        $user = Auth::user();

        return $this->model
            ->where('user_id', $user->id)
            ->latest()->paginate();
    }

    public function store($product_id)
    {
        $user     = Auth::user();
        $favorite = $this->model->where('user_id', $user->id)
            ->where('product_id', $product_id)
            ->first();

        if ($favorite) {
            $favorite->delete();

            return $this->okResponse(null, __('Product removed from favorites'));

        }
        $favorite = $this->model->create([
            'user_id'    => $user->id,
            'product_id' => $product_id,
            'favorite'   => true,
        ]);
        return $this->okResponse(null, __('Add Favaroite Successfully'));

    }
}
