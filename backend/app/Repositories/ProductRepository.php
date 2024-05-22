<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductRequest;

class ProductRepository
{
    public function getGroupedItems($arr, $name)
    {
        return $arr->groupBy($name)->map(function ($products, $brand) {
            return [
                'name' => $brand,
                'count' => $products->count(),
            ];
        })->values();
    }

    public function namingFilters($filter)
    {
        switch ($filter) {
            case "brand":
                return "Prekės ženklas";
            case "model":
                return "Modelis";
            case "delivery_time":
                return "Pristatymo laikas (dienos)";
            case "condition":
                return "Būklė";
            case "price":
                return "Kaina";
            default:
                return "";
        }
    }
    public function getAllFiltersAndProducts($path, Request $request)
    {
        $category = Category::where('path', $path)->firstOrFail();
        $productsQuery = Product::where('category_id', $category->id);

        $parameters = $request->all();

        foreach ($parameters as $key => $param) {
            $paramArr = explode('%', $param);
            $productsQuery->whereIn($key, $paramArr);
        }

        $filters = ['brand', 'model', 'delivery_time', 'condition', 'price'];

        $products = $productsQuery->get();

        $groupedFilters = collect($filters)->map(function ($filter) use ($parameters, $category, $products) {
            if (array_key_first($parameters) === $filter) {
                $allProducts = Product::where('category_id', $category->id)->get();
                return $this->getGroupedItems($allProducts, $filter);
            } else {
                return $this->getGroupedItems($products, $filter);
            }
        });

        return response()->json([
            'products' => $products,
            'filters' => $groupedFilters->map(function ($items, $index) use ($filters) {
                return [
                    'name' => $this->namingFilters($filters[$index]),
                    'table_name' => $filters[$index],
                    'items' => $items
                ];
            }),
        ]);
    }

    public function createProductXml(Request $request)
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
}