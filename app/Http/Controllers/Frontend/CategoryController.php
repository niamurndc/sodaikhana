<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    public function show($id){
        
        $cat_title = DB::table('categories')->where('id', $id)->value('title');

        $products = DB::table('products')
            ->join('category_product', 'products.id', '=', 'category_product.product_id')
            ->join('categories', 'category_product.category_id', '=', 'categories.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->where('categories.id', $id)
            ->select('products.*', 'brands.title AS brand_name')
            ->get();

        //return ProductResource::collection($products);
        return response(['data' => ['title' => $cat_title, 'products' => ProductResource::collection($products)]]);
    }
    

    
}

