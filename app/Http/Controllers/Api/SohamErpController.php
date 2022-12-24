<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Soham\Category;
use App\Models\Soham\Product;
use App\Models\Soham\ProductPrice;
use Illuminate\Http\Request;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;

class SohamErpController extends Controller
{
    use HttpResponseTraits;

    /**
     * Get all category
     *
     * @param Request $request
     * @return json
     */
    public function categoryList(Request $request)
    {
        $perPage = !empty($request->perPage) ? $request->perPage : config('constants.PER_PAGE');
        $from = !empty($request->from) ? $request->from : 0;
        $to = !empty($request->to) ? $request->to : 0;
        $sort = !empty($request->sort) ? $request->sort : 'ASC';

        // Get Category Data
        $category = Category::select([
            'id as soham_category_id',
            'name as category_name'
        ]);

        // Filter Category
        if (!empty($request->filter)) {
            $category->where('name', 'like', "%$request->filter%");
        }

        // Sorting And Limit
        $categoryData = $category->limit($perPage)
            ->orderBy('name', $sort)
            ->get();

        // Return Not Found.
        if ($categoryData->isEmpty()) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }

        // Return Category Data
        return $this->success(Lang::get('messages.success'), ['categories' => $categoryData]);
    }

    /**
     * Get All Products
     *
     * @param Request $request
     * @return json
     */
    public function getProducts(Request $request)
    {
        $perPage = !empty($request->perPage) ? $request->perPage : config('constants.PER_PAGE');
        $from = !empty($request->from) ? $request->from : 0;
        $to = !empty($request->to) ? $request->to : 0;
        $sort = !empty($request->sort) ? $request->sort : 'ASC';

        // Get Product Data
        $product = Product::with('category')->with(['product_price' => function ($query) {
            $query->select(['product_id', 'qty', 'price']);
        }]);

        // Filter Product
        if (!empty($request->filter)) {
            $product->whereHas('category', function ($query) use ($request) {
                if (!empty($request->filter)) {
                    $query->where('categories.name', 'like', "%$request->filter%");
                }
            });
            $product->orWhere('name', 'like', "%$request->filter%");
        }

        // GEt Category Base Products
        if (!empty($request->category_id)) {
            $product->where('category_id', $request->category_id);
        }

        // Sorting And Limit
        $productData = $product->limit($perPage)
            ->orderBy('name', $sort)->get();

        // Return Not Found.
        if ($productData->isEmpty()) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }

        // Format Data
        $productData = $productData->map(function ($item) {
            $productPrice = [];
            if (!empty($item->product_price)) {
                $item->product_price->makeHidden('product_id');
                $productPrice = $item->product_price->all();
            }
            return [
                "soham_product_id" => $item->id,
                "ProductCode" => $item->code,
                "ProductName" => $item->name,
                "ProductPack" => $item->pack,
                "ProductUnit" => $item->unit,
                "ProductCatagory" => $item->category->name,
                "ProductQty" => $item->qty,
                "description" => $item->description,
                "ProductPrice" =>  $productPrice,
            ];
        });

        // Return Product Data
        return $this->success(Lang::get('messages.success'), ['products' => $productData]);
    }

    /**
     * Get Product Details
     *
     * @param Request $request
     * @return void
     */
    public function getProductDetails(Request $request)
    {
        $product = Product::find($request->soham_product_id);

        // Return Not Found.
        if (empty($product)) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }

        $productPrice = [];
        if (!empty($product->product_price)) {
            $product->product_price->makeHidden('product_id');
            $productPrice = $product->product_price->all();
        }

        $productData = [
            "soham_product_id" => $product->id,
            "ProductCode" => $product->code,
            "ProductName" => $product->name,
            "ProductPack" => $product->pack,
            "ProductUnit" => $product->unit,
            "ProductCatagory" => $product->category->name,
            "ProductQty" => $product->qty,
            "description" => $product->description,
            "ProductPrice" =>  $productPrice,
        ];

        // Return Product Data
        return $this->success(Lang::get('messages.success'), ['product_details' => $productData]);
    }
}
