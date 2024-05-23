<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;


class ProductRepository
{

    public function findCategoryByPath($path)
    {
        return Category::where('path', $path)->firstOrFail();
    }
    public function getProductsByCategory($categoryId)
    {
        return Product::where('category_id', $categoryId)->get();
    }
    public function getProductQueryByCategoryId($categoryId)
    {
        return Product::where('category_id', $categoryId);
    }
    public function getAllProducts()
    {
        return Product::all();
    }

}