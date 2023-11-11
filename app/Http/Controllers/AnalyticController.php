<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Models\Area;
use App\Models\Cart;
use App\Models\DailyReport;
use App\Models\Expance;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticController extends Controller
{
    public function sales(){
        
        if(isset($_GET['from']) && isset($_GET['to'])){

            $from = $_GET['from'];
            $to = $_GET['to'];
            $orders = Order::orderBy('id', 'desc')->where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();

        }else{

            $today = Carbon::today();
            $orders = Order::orderBy('id', 'desc')->whereDate('created_at', $today)->get();
        }
        return OrderResource::collection($orders);
    }

    public function products(){
        
        if(isset($_GET['from']) && isset($_GET['to'])){

            $from = $_GET['from'];
            $to = $_GET['to'];
            $carts = Cart::orderBy('product_id', 'desc')->where('created_at', '>=', $from)->where('created_at', '<=', $to)->get();

        }else{

            $today = Carbon::today();
            $carts = Cart::orderBy('product_id', 'desc')->whereDate('created_at', $today)->get();

        }

        $productID = 0;
        $idList = [];
        foreach($carts as $cart){
            if($cart->product_id != $productID){
                if(!in_array($cart->product_id, $idList)){
                    array_push($idList, $cart->product_id);
                }    
            }
            $productID = $cart->product_id;
        }

        $cart_list = [];

        foreach($idList as $id){
            $product = Product::find($id);
            if(isset($_GET['from']) && isset($_GET['to'])){
                $count = Cart::where('product_id', $id)->where('created_at', '>=', $from)->where('created_at', '<=', $to)->sum('quantity');
            }else{
                $count = Cart::where('product_id', $id)->whereDate('created_at', $today)->sum('quantity');
            }

            $cart = [
                'name' => $product->title,
                'qty' => $count
            ];

            array_push($cart_list, $cart);
        }

        return response()->json($cart_list);
    }

    public function profit(){
        $today = Carbon::today();
        $order_count = count(Order::whereDate('created_at', $today)->get());
        $order_total = Order::whereDate('created_at', $today)->sum('subtotal');
        $order_paid = Order::whereDate('created_at', $today)->sum('paid');
        $order_due = Order::whereDate('created_at', $today)->sum('due');
        $expance = Expance::whereDate('created_at', $today)->sum('amount');

        return response([
            'order_count' => $order_count,
            'order_total' => $order_total,
            'order_paid' => $order_paid,
            'order_due' => $order_due,
            'expance' => $expance,
        ]);
    }

    public function dailyReports(){
        $reports = DailyReport::all();
        return response()->json($reports);
    }

    public function addReport(Request $request){
        $today = Carbon::today();
        $report = DailyReport::whereDate('created_at', $today)->first();
        if($report != null){
            $report->orders = $request->orders;
            $report->total = $request->total;
            $report->paid = $request->paid;
            $report->due = $request->due;
            $report->expance = $request->expance;
            $report->cash = $request->cash;

            $report->update();
        }else{
            $nreport = new DailyReport();
            $nreport->orders = $request->orders;
            $nreport->total = $request->total;
            $nreport->paid = $request->paid;
            $nreport->due = $request->due;
            $nreport->expance = $request->expance;
            $nreport->cash = $request->cash;

            $nreport->save();
        }

        return response(['message', 'Report updated']);
    }
}
