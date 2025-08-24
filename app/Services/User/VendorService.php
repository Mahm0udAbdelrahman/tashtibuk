<?php
namespace App\Services\User;

use App\Models\Vendor;

class VendorService
{
    public function __construct(public Vendor $model)
    {}

    public function index()
    {
        return $this->model->paginate();
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function productVendor($id)
    {
        $vendor = $this->model->findOrFail($id);

        return $vendor->products()->paginate();
    }

}
