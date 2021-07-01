<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        
    }

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
        return ProductResource::collection(Product::where('offer', 'on')->limit(6)->get());
    }

    public function popular(){
        return ProductResource::collection(Product::where('popular', 'on')->limit(8)->get());
    }
}
