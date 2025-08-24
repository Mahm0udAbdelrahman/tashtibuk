<?php
namespace App\Services\Dashboard;

use App\Models\Delivery;
use App\Traits\HasImage;

class DeliveryService
{
    use HasImage;
    public function __construct(public Delivery $model)
    {}

    public function index()
    {
        return $this->model->latest()->paginate();
    }

    public function store($data)
    {
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'delivery');
        } else {
            $data['image'] = asset('default/default.png');
        }

        if (isset($data['logo'])) {
            $data['logo'] = $this->saveImage($data['logo'], 'delivery');
        }
        if (isset($data['background'])) {
            $data['background'] = $this->saveImage($data['background'], 'delivery');
        }
        return $this->model->create($data);
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, $data)
    {
        $delivery = $this->show($id);
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'delivery');
        } else {
            $data['image'] = asset('default/default.png');
        }

        if (isset($data['logo'])) {
            $data['logo'] = $this->saveImage($data['logo'], 'delivery');
        }
        if (isset($data['background'])) {
            $data['background'] = $this->saveImage($data['background'], 'delivery');
        }
         $delivery->update($data);
         return $delivery;
    }

    public function delete($id)
    {
        $delivery = $this->show($id);
        $delivery->delete();
    }

}
