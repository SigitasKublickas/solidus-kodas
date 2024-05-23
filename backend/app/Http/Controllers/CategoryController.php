<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id = null)
    {
        if ($id == null) {
            // Get all top-level categories with their child categories recursively
            $categories = Category::with('cat_childs')
                ->whereNull('parent_id')
                ->get()
                ->map(function ($category) {
                    return $this->getCategoryWithChildren($category);
                });

            return response()->json($categories);
        } else {
            // Find the category by path and load its child categories recursively
            $category = Category::where("path", $id)->firstOrFail();

            $categoryTree = $this->getCategoryWithChildren($category);

            return response()->json($categoryTree);
        }
    }

    private function getCategoryWithChildren($category)
    {
        $category->load('cat_childs');

        if ($category->cat_childs->isNotEmpty()) {
            $category->cat_childs = $category->cat_childs->map(function ($child) {
                return $this->getCategoryWithChildren($child);
            });
        }
        return $category;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        return $request;
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category = Category::with([
            'cat_childs' => function ($query) {
                $query->with('cat_childs'); // Recursive loading for deeper nesting
            }
        ])->find($category->id);
        return response()->json($category);
    }
    public function showCategoryOrProductsData($category_path)
    {
        $categoryId = Category::where("path", $category_path)->first();
        $category = Category::with(['cat_childs'])->findOrFail($categoryId->id);

        if ($category->cat_childs->isNotEmpty()) {
            return response()->json($category->cat_childs);
        }

    }
    public function getWithoutChild()
    {
        $categories = Category::doesntHave('cat_childs')->get();
        $formattedCategories = $categories->map(function ($category) {
            return [
                'value' => $category->id,
                'name' => $category->name
            ];
        });

        return response()->json($formattedCategories);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
