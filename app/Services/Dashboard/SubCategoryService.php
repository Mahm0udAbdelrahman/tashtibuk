<?php
namespace App\Services\Dashboard;

use App\Models\SubCategory;

class SubCategoryService
{
    public function __construct(public SubCategory $model)
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
        $sub_category = $this->show($id);
         $sub_category->update($data);
         return $sub_category;
    }

    public function delete($id)
    {
        $sub_category = $this->show($id);
        $sub_category->delete();
    }

}
