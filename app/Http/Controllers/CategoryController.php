<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{


    public function index(){
        $cats = Category::all();
        return CategoryResource::collection($cats);
    }


    public function show($id){
        return new CategoryResource(Category::findOrFail($id));
    }
    

    public function store(Request $req){
        $req->validate([
            'title' => 'required|string'
        ]);
        
        $category = new Category();

        $category->title = $req->title;
        $category->slug = Str::slug($req->title);
        $category->image = 'coffee.jpg';
        $category->parent = $req->parent;

        $category->save();
        
        return new CategoryResource($category);
    }


    public function update(Request $req, $id){
        $req->validate([
            'title' => 'required|string'
        ]);

        $category = Category::find($id);

        $category->title = $req->title;
        $category->slug = Str::slug($req->title);
        $category->parent = $req->parent;

        $category->update();
        return new CategoryResource($category);
    }

    public function delete($id){
        $category = Category::find($id);
        $category->delete();

        return response(['message' => 'Deleted Successful']);
    }
}

