<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getGroupedItems($arr, $name)
    {
        $group = $arr->groupBy($name);
        return $group->map(function ($products, $brand) {
            return [
                'name' => $brand,
                'count' => $products->count(),
            ];
        })->values();

    }

    public function index($category_path, Request $request)
    {
        $category = Category::where('path', $category_path)->first();
        $productsQuery = Product::where("category_id", $category->id);

        $parameters = $request->all();
        foreach ($parameters as $key => $param) {
            $paramArr = explode("%", $param);
            $productsQuery->whereIn($key, $paramArr);
        }
        $filters = ["brand", "model", "delivery_time", "condition", "price"];

        $products = $productsQuery->get();

        $group = [];
        foreach ($filters as $item) {
            if (array_key_first($parameters) == $item) {
                $pro = Product::where("category_id", $category->id)->get();
                array_push($group, $this->getGroupedItems($pro, $item));
            } else {
                array_push($group, $this->getGroupedItems($products, $item));
            }
        }




        return response()->json(
            [
                [
                    "products" => $products,
                    "filters" => [
                        [
                            "name" => "Gamintojas",
                            'table_name' => "brand",
                            "items" => $group[0]
                        ],
                        [
                            "name" => "Modelis",
                            'table_name' => "model",
                            "items" => $group[1],
                        ],
                        [
                            "name" => "Pristatymo laikas",
                            'table_name' => "delivery_time",
                            "items" => $group[2],
                        ],
                        [
                            "name" => "Būklė",
                            'table_name' => "condition",
                            "items" => $group[3],
                        ],
                        [
                            "name" => "Kaina",
                            'table_name' => "price",
                            "items" => $group[4],
                        ]
                    ]
                ],


            ]
        );

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
                $requestClassInstance = new StoreProductRequest();
                $rules = $requestClassInstance->rules();
                foreach ($products as $item) {
                    $validator = Validator::make($item, $rules);
                    if ($validator->fails()) {
                        return response()->json(['message' => $validator->errors()], 400);
                    }

                    Product::create($validator->validated());
                }

                return response()->json(['message' => 'Sėkmingai sukurta'], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], 400);
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
