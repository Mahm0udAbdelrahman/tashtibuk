<?php

namespace App\Services\User;

use App\Models\Category;


class CategoryService
{
    public function __construct(public Category $category){}

    public function index()
    {
        return $this->category->paginate();
    }

    

}
