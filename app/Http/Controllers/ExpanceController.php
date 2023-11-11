<?php

namespace App\Http\Controllers;

use App\Models\Expance;
use Illuminate\Http\Request;

class ExpanceController extends Controller
{
    public function index(){
        if(isset($_GET['from']) && isset($_GET['to'])){
            $from = $_GET['from'];
            $to = $_GET['to'];
            $expance = Expance::orderBy('id', 'desc')->where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();
        }else{
            $expance = [];
        }

        return response($expance);
    }

    public function store(Request $request){
        $expance = new Expance();
        $expance->details = $request->detail;
        $expance->amount = $request->amount;
        
        if($request->created_at != null){
            $expance->created_at = $request->created_at;
        }

        $expance->save();
        
        return response(['message' => 'Expance added']);
    }
    

    
}
