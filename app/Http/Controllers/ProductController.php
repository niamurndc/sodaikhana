<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(){
        return ProductResource::collection(Product::paginate(50));
    }


    public function show($id){
        return new ProductResource(Product::findOrFail($id));
    }
    
    public function store(Request $req){
        $req->validate([
            'title' => 'required|string',
            'price' => 'numeric|required',
            'tags' => 'required',
            'brand_id' => 'required',
        ]);




        $product = new Product();

        $product->title = $req->title;
        $product->slug = Str::slug($req->title);
        $product->attri = $req->attri;
        $product->desc = $req->desc;
        $product->price = $req->price;
        $product->offprice = $req->offprice;
        $product->offer = $req->offer;
        $product->popular = $req->popular;
        $product->attri1 = $req->attri1;
        $product->val1 = $req->val1;
        $product->attri2 = $req->attri2;
        $product->val2 = $req->val2;
        $product->attri3 = $req->attri3;
        $product->val3 = $req->val3;
        $product->stock_out = $req->stock_out;
        $product->tags = $req->tags;
        $product->brand_id = $req->brand_id;

        // add image
        $image = $req->file('image');
        if($image != null){
            $ext = $image->getClientOriginalExtension();
            $cover = 'pro-'.time().'.'.$ext;
            $product->image = $cover;
            $path = 'uploads';
            $image->move($path, $cover);
        }else{
            $product->image = 'coffee.jpg';
        }

        $product->save();

        $product->categories()->attach($req->category);

        return new ProductResource($product);
    }

    public function update(Request $req, $id){
        $req->validate([
            'title' => 'required|string',
            'price' => 'numeric|required',
            'tags' => 'required',
            'brand_id' => 'required',
        ]);


        $product = Product::find($id);

        $product->title = $req->title;
        $product->slug = Str::slug($req->title);
        $product->attri = $req->attri;
        $product->desc = $req->desc;
        $product->price = $req->price;
        $product->offprice = $req->offprice;
        $product->offer = $req->offer;
        $product->popular = $req->popular;
        $product->attri1 = $req->attri1;
        $product->val1 = $req->val1;
        $product->attri2 = $req->attri2;
        $product->val2 = $req->val2;
        $product->attri3 = $req->attri3;
        $product->val3 = $req->val3;
        $product->stock_out = $req->stock_out;
        $product->tags = $req->tags;
        $product->brand_id = $req->brand_id;

        $product->categories()->sync($req->category);

        // add image
        $image = $req->file('image');
        if($image != ''){
            $ext = $image->getClientOriginalExtension();
            $cover = 'pro-'.time().'.'.$ext;
            $product->image = $cover;
            $path = 'uploads';
            $image->move($path, $cover);
        }

        $product->update();
        return new ProductResource($product);
    }

    public function delete($id){
        $product = Product::findOrFail($id);
        $product->categories()->detach();
        
        $carts = Cart::where('product_id', $id)->get();
        foreach($carts as $cart){
            $cart->delete();
        }
        $product->delete();

        return response(['message' => 'Deleted Successful']);
    }
}
