<?php
namespace App\Services\Dashboard;

use App\Models\Vendor;
use App\Traits\HasImage;

class VendorService
{
    use HasImage;
    public function __construct(public Vendor $model)
    {}

    public function index()
    {
        return $this->model->latest()->paginate();
    }

    public function store($data)
    {
       

        if (isset($data['logo'])) {
            $data['logo'] = $this->saveImage($data['logo'], 'Vendor');
        }
        if (isset($data['background'])) {
            $data['background'] = $this->saveImage($data['background'], 'Vendor');
        }
        return $this->model->create($data);
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, $data)
    {
        $vendor = $this->show($id);
        

        if (isset($data['logo'])) {
            $data['logo'] = $this->saveImage($data['logo'], 'Vendor');
        }
        if (isset($data['background'])) {
            $data['background'] = $this->saveImage($data['background'], 'Vendor');
        }
         $vendor->update($data);
         return $vendor;
    }

    public function delete($id)
    {
        $vendor = $this->show($id);
        $vendor->delete();
    }

}
