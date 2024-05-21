<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($category_path, Request $request)
    {
        $category = Category::where('path', $category_path)->first();
        $productsQuery = Product::where("category_id", $category->id);

        $parameters = $request->all();
        foreach ($parameters as $key => $param) {
            $paramArr = explode("%", $param);
            $productsQuery->whereIn($key, $paramArr);
        }

        $products = $productsQuery->get();

        $groupedProductsByBrand = $products->groupBy('brand');
        $groupedProductsByModel = $products->groupBy('model');
        $groupedProductsByDeliveryTime = $products->groupBy('delivery_time');
        $groupedProductsByCondition = $products->groupBy('condition');


        $brandCounts = $groupedProductsByBrand->map(function ($products, $brand) {
            return [
                'name' => $brand,
                'count' => $products->count(),
            ];
        });
        $modelCounts = $groupedProductsByModel->map(function ($products, $model) {
            return [
                'name' => $model,
                'count' => $products->count(),
            ];
        });

        $deliveryCount = $groupedProductsByDeliveryTime->map(function ($products, $time) {
            return [
                'name' => $time,
                'count' => $products->count(),
            ];
        });
        $conditionCount = $groupedProductsByCondition->map(function ($products, $condition) {
            return [
                'name' => $condition,
                'count' => $products->count(),
            ];
        });


        // $maxPrice = $products->max('price');
        // $minPrice = $products->min('price');


        return response()->json([
            [
                "products" => $products,
                "filters" => [
                    [
                        "name" => "Gamintojas",
                        'table_name' => "brand",
                        "items" => $brandCounts->values(),
                    ],
                    [
                        "name" => "Modelis",
                        'table_name' => "model",
                        "items" => $modelCounts->values(),
                    ],
                    [
                        "name" => "Pristatymo laikas",
                        'table_name' => "delivery_time",
                        "items" => $deliveryCount->values(),
                    ],
                    [
                        "name" => "Būklė",
                        'table_name' => "condition",
                        "items" => $conditionCount->values(),
                    ]
                ]
            ],


        ]);

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
        if ($request->header('Content-Type') === 'application/xml') {
            $xmlData = $request->getContent();

            try {
                $data = simplexml_load_string($xmlData, "SimpleXMLElement");

                $json = json_encode($data);
                $array = json_decode($json, true);
                print_r($array);

                $products = isset($array['product'][0]) ? $array['product'] : [$array['product']];
                foreach ($products as $item) {
                    $validatedData = $request->validated([
                        'name' => $item['name'],
                        'desc' => $item['desc'],
                        'price' => $item['price'],
                        'delivery_time' => $item['delivery_time'],
                        'stock' => $item['stock'],
                        'img_url' => $item['img_url'],
                        'category_id' => $item['category_id'],
                        'brand' => $item['brand'],
                        'model' => $item['model'],
                        'condition' => $item['condition'],
                    ]);
                    Product::create($validatedData);
                }

                return response()->json(['message' => 'Sėkmingai sukurta'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Klaida! Patikrinkite ar suvedėte teisinga informacija'], 400);
            }
        } else {
            return response()->json(['message' => 'Klaida! Xml formatas netinkamas'], 400);
        }
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
    //get all products by subcategory_id
    public function showAllByPath($category_id)
    {
        $products = Product::where('category_id', $category_id)->get();
        if ($products->isEmpty()) {
            return ['message' => 'Produktu nerasta pagal šią kategoriją !'];
        }
        return $products;

    }
}
