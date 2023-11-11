<?php

namespace App\Http\Controllers;

use App\Http\Resources\AreaResource;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(){
        return AreaResource::collection(Area::all());
    }
    

    public function store(Request $req){
        $req->validate([
            'title' => 'required|string'
        ]);

        $area = new Area();
        $area->title = $req->title;
        $area->save();
        
        return new AreaResource($area);
    }

    public function delete($id){
        $area = Area::findOrFail($id);
        $area->delete();

        return response(['message' => 'Deleted Successful']);
    }
}
