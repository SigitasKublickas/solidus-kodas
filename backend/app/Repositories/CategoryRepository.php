<?php

namespace App\Repositories;

use App\Models\Category;
use App\Http\Services\CategoryService;

class CategoryRepository
{

    protected $categoryService;
    function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function getByPath($path)
    {
        $category = Category::where("path", $path)->firstOrFail();
        return $category;
    }
    public function getAll()
    {
        return Category::get();
    }
    public function getTopLevelCategories()
    {
        return Category::with('cat_childs')
            ->whereNull('parent_id')
            ->get()
            ->map(function ($category) {
                return $this->categoryService->getCategoryWithChildren($category);
            });
    }

    public function getCategoriesTree($path)
    {
        $category = $this->getByPath($path);

        $categoryTree = $this->categoryService->getCategoryWithChildren($category);

        return $categoryTree;
    }
    public function getWithoutChilds()
    {
        $categories = Category::doesntHave('cat_childs')->get();
        $formattedCategories = $categories->map(function ($category) {
            return [
                'value' => $category->id,
                'name' => $category->name
            ];
        });
        return $formattedCategories;
    }
}
