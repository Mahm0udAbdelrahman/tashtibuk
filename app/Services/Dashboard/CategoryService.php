<?php
namespace App\Services\Dashboard;

use App\Models\Category;
use App\Traits\HasImage;

class CategoryService
{
    use HasImage;
    public function __construct(public Category $model)
    {}

    public function index()
    {
        return $this->model->paginate();
    }

    public function store($data)
    {
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'category');
        }
        return $this->model->create($data);
    }

    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    public function update($id, $data)
    {
        $category = $this->show($id);
        if (isset($data['image'])) {
            $data['image'] = $this->saveImage($data['image'], 'category');
        }
         $category->update($data);
         return $category;
    }

    public function delete($id)
    {
        $category = $this->show($id);
        $category->delete();
    }

}
