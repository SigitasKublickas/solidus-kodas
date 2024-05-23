<?php

namespace App\Http\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductRequest;

use App\Models\Product;

class ProductService
{
    public $filterValues;
    protected $productRepository;
    protected $categoryRepository;

    function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->filterValues = ['brand', 'model', 'delivery_time', 'condition', 'price'];
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
    public function getFilteredProductsByParams($query, Request $request)
    {
        $parameters = $request->all();

        foreach ($parameters as $key => $param) {
            $paramArr = explode('%', $param);
            $query->whereIn($key, $paramArr);
        }

        return $query->get();
    }
    public function getGroupedItems($arr, $name)
    {
        return $arr->groupBy($name)->map(function ($products, $brand) {
            return [
                'name' => $brand,
                'count' => $products->count(),
            ];
        })->values();
    }

    public function filters($filteredProducts, $category, Request $request)
    {
        $parameters = $request->all();
        $grouped = collect($this->filterValues)->map(function ($filter) use ($parameters, $category, $filteredProducts) {
            if (array_key_first($parameters) === $filter) {
                $allProducts = Product::where('category_id', $category->id)->get();
                return $this->getGroupedItems($allProducts, $filter);
            } else {
                return $this->getGroupedItems($filteredProducts, $filter);
            }
        });
        return $grouped;
    }
    public function getAllFiltersAndProducts($path, Request $request)
    {
        $category = $this->categoryRepository->getByPath($path);

        $productsQuery = $this->productRepository->getProductQueryByCategoryId($category->id);

        $filters = $this->filterValues;
        $products = $this->getFilteredProductsByParams($productsQuery, $request);

        $groupedFilters = $this->filters($products, $category, $request);

        return response()->json([
            'category_name' => $category->name,
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

    public function create(StoreProductRequest $request)
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

}