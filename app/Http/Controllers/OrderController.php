<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        //$this->middleware('admin');
    }

    public function index(){
        return OrderResource::collection(Order::orderBy('id', 'desc')->get());
    }


    public function show($id){
        return new OrderResource(Order::findOrFail($id));
    }
    
    public function store(Request $req){
        $req->validate([
            'name' => 'required|string',
            'phone' => 'numeric|required',
            'area' => 'string|required',
            'address' => 'string|required',
            'subtotal' => 'numeric|required',
        ]);

        $order = new Order();

        $order->name = $req->name;
        $order->phone = $req->phone;
        $order->area = $req->area;
        $order->address = $req->address;
        $order->note = $req->note;
        $order->subtotal = $req->subtotal;
        $order->status = 0;


        $order->save();

        foreach($req->carts as $req_cart){

            $cart = new Cart();

            $cart->order_id = $order->id;
            $cart->is_order = 1;
            $cart->product_id = $req_cart['product_id'];
            $cart->attri = $req_cart['attri'];
            $cart->quantity = $req_cart['quantity'];
            $cart->skey = "sodaikhana";
            $cart->user_id = 1; 

            $cart->save();
        }

        // send sms
        $url = "http://gsms.putulhost.com/smsapi";
        $data = [
            "api_key" => "C20002265fc3193d4ed399.63654261",
            "type" => "text",
            "contacts" => "8801980672702",
            "senderid" => "8809601001272",
            "msg" => "A new order come in Sodaikhana.com",
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);

        return new OrderResource($order);
    }

    public function update(Request $req, $id){
        $order = Order::find($id);

        $order->status = $req->status;

        $order->update();
        return new OrderResource($order);
    }

    public function delete($id){
        $order = Order::find($id);
        $order->delete();

        return response(['message' => 'Order Deleted Successfuly']);
    }
    
    public function ordersms(Request $req){
        
        $url = "http://gsms.putulhost.com/smsapi";
        $data = [
            "api_key" => "C20002265fc3193d4ed399.63654261",
            "type" => "text",
            "contacts" => "88".$req->phone,
            "senderid" => "8809601001272",
            "msg" => $req->smstext,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);
        
        return response(['message' => 'Order SMS send Successfuly']);
    }
}
