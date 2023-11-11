<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index($phone){
        return OrderResource::collection(Order::where('phone', $phone)->orderBy('id', 'desc')->get());
    }
    
}

