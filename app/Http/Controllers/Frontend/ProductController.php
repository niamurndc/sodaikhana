<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    

    public function index(){
        return ProductResource::collection(Product::all());
    }

    public function search($id){
        $products = Product::where('title', 'LIKE', '%'.$id.'%')->orWhere('tags', 'LIKE', '%'.$id.'%')->get();
        return ProductResource::collection($products);
    }

    public function show($id){
        return new ProductResource(Product::findOrFail($id));
    }


    public function offer(){
        $products = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->where('products.offer', 'on')
            ->select('products.*', 'brands.title AS brand_name')
            ->limit(30)
            ->get();
        return ProductResource::collection($products);
    }

    public function popular(){
        $products = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->where('products.popular', 'on')
            ->select('products.*', 'brands.title AS brand_name')
            ->limit(40)
            ->get();
        return ProductResource::collection($products);
    }
    
    public function food(){
        $products = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->where('products.food', 'on')
            ->select('products.*', 'brands.title AS brand_name')
            ->limit(30)
            ->get();
        return ProductResource::collection($products);
    }
}
