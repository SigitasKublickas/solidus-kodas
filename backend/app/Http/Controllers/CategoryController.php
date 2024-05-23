<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Http\Services\CategoryService;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
    protected $categoryRepository;
    protected $categoryService;
    function __construct(CategoryRepository $categoryRepository, CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
        $this->categoryRepository = $categoryRepository;
    }
    public function index($path = null)
    {
        $categories = $path == null ? $this->categoryRepository->getTopLevelCategories() : $this->categoryRepository->getCategoriesTree($path);

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

    }
    public function getWithoutChild()
    {
        $categories = $this->categoryRepository->getWithoutChilds();

        return response()->json($categories);
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
