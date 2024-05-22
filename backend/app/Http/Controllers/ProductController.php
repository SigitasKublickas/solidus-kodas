<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index($path, Request $request)
    {
        return $this->productRepository->getAllFiltersAndProducts($path, $request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function storeXml(Request $request)
    {
        return $this->productRepository->createProductXml($request);
    }
    public function store(StoreProductRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $product = Product::create($validatedData);

            return response()->json([
                'message' => 'Produktas sukurtas sėkmingai!',
                'product' => $product
            ], 201);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];

            if ($errorCode === 1062) {
                return response()->json([
                    'message' => 'Validation error.',
                    'errors' => [
                        'img_url' => ['Klaida! Toks nuotraukas pavadinimas jau naudojamas!'],
                    ],
                ], 422);
            }

            return response()->json([
                'message' => 'Klaida! Nepavyko sukurti produkto!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

}
