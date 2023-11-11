<?php

namespace App\Http\Controllers;

use App\Http\Resources\SmsItemResource;
use App\Http\Resources\SmsListResource;
use App\Models\SmsItem;
use Illuminate\Http\Request;

class SmsItemController extends Controller
{

    public function store(Request $request){
        
        $item = new SmsItem();

        $item->name = $request->name;
        $item->phone = $request->phone;
        $item->list_id = $request->list_id;

        $item->save();
        
        return new SmsItemResource($item);
    }

    public function delete($id){
        $request = SmsItem::find($id);
        $request->delete();

        return response(['message' => 'Deleted Successful']);
    }
}
