<?php

namespace App\Http\Services;

class CategoryService
{
    public function getCategoryWithChildren($category)
    {
        $category->load('cat_childs');

        if ($category->cat_childs->isNotEmpty()) {
            $category->cat_childs = $category->cat_childs->map(function ($child) {
                return $this->getCategoryWithChildren($child);
            });
        }
        return $category;
    }

}