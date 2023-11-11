<?php

namespace App\Http\Controllers;

use App\Http\Resources\MyDayResource;
use App\Models\MyDay;
use Illuminate\Http\Request;

class MyDayController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(){
        return MyDayResource::collection(MyDay::all());
    }

    public function show($id){
        return new MyDayResource(MyDay::findOrFail($id));
    }
    

    public function store(Request $req){
        $req->validate([
            'image' => 'required|image',
            'link' => 'required|string'
        ]);

        $story = new MyDay();

        $story->link = $req->link;
        $story->image = 'coffee.jpg';

        $image = $req->file('image');
        $ext = $image->getClientOriginalExtension();
        $cover = 'story-'.time().'.'.$ext;
        $story->image = $cover;
        $path = 'uploads/story';
        $image->move($path, $cover);

        $story->save();
        
        return new MyDayResource($story);
    }


    public function delete($id){
        $story = MyDay::findOrFail($id);
        $story->delete();

        return response(['message' => 'Deleted Successful']);
    }
}
