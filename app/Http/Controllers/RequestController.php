<?php

namespace App\Http\Controllers;

use App\Http\Resources\RequestResource;
use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    

    public function index(){
        return RequestResource::collection(ModelsRequest::all());
    }
    

    public function store($id){
        
        $request = new ModelsRequest();

        $request->name = auth()->user()->name;
        $request->phone = auth()->user()->phone;
        $request->product_id = $id;

        $request->save();
        
        return new RequestResource($request);
    }

    public function delete($id){
        $request = ModelsRequest::find($id);
        $request->delete();

        return response(['message' => 'Deleted Successful']);
    }
}
