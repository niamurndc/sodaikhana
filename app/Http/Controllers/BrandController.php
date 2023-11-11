<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(){
        return BrandResource::collection(Brand::all());
    }

    public function show($id){
        return new BrandResource(Brand::findOrFail($id));
    }
    

    public function store(Request $req){
        $req->validate([
            'title' => 'required|string'
        ]);

        $brand = new Brand();

        $brand->title = $req->title;
        $brand->slug = Str::slug($req->title); 
        $brand->image = 'coffee.jpg';

        $brand->save();
        
        return new BrandResource($brand);
    }

    public function update(Request $req, $id){
        $req->validate([
            'title' => 'required|string'
        ]);

        $brand = Brand::find($id);

        $brand->title = $req->title;
        $brand->slug = Str::slug($req->title);

        $brand->update();
        return new BrandResource($brand);
    }

    public function delete($id){
        $brand = Brand::findOrFail($id);

        $products = Product::where('brand_id', $id);
        foreach($products as $product){
            $product->brand_id = 0;
            $product->update();
        }

        $brand->delete();

        return response(['message' => 'Deleted Successful']);
    }
}
