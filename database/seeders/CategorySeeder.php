<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name_ar' => 'تصنيف 1',
                'name_en' => 'Category 1',
                'status'  => 1,
            ],
            [
                'name_ar' => 'تصنيف 2',
                'name_en' => 'Category 2',
                'status'  => 1,
            ],
            [
                'name_ar' => 'تصنيف 3',
                'name_en' => 'Category 3',
                'status'  => 1,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
