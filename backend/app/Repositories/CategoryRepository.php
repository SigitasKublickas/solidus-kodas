<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function getByPath($path)
    {
        $category = Category::where("path", $path)->firstOrFail();
        return $category;
    }
    public function getAll()
    {
        return Category::get();
    }
}
