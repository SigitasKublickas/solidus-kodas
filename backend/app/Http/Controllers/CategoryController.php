<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with([
            'cat_childs' => function ($query) {
                $query->with('cat_childs'); // Recursive loading for deeper nesting
            }
        ])->whereNull('parent_id')->get();

        return response()->json($categories);

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
        $category = Category::with(['cat_childs', 'products'])->findOrFail($categoryId->id);

        if ($category->cat_childs->isNotEmpty()) {
            return response()->json([
                'type' => 'child_categories',
                'data' => $category->cat_childs,
            ]);
        }

        return response()->json([
            'type' => 'products',
            'data' => $category->products,
        ]);

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
