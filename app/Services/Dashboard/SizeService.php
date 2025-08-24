<?php
namespace App\Services\Dashboard;

use App\Models\Size;

class SizeService
{
    public function __construct(public Size $model)
    {}

    public function index()
    {
        return $this->model->paginate();
    }

    public function store($data)
    {
        return $this->model->create($data);
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, $data)
    {
        $size = $this->show($id);
         $size->update($data);
         return $size;
    }

    public function delete($id)
    {
        $size = $this->show($id);
        $size->delete();
    }

}
