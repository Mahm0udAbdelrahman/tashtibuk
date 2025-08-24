<?php
namespace App\Services\Dashboard;

use App\Models\Order;
use App\Traits\HasImage;

class OrderService
{
    use HasImage;
    public function __construct(public Order $model)
    {}

    public function index()
    {
        return $this->model->latest()->paginate();
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function destroy($id)
    {
        $order = $this->show($id);
        $order->delete();
        return $order;
    }

}
