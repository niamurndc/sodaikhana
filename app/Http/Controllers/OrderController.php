<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Setting;
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
        $order->paid = $req->paid;
        $order->due = $req->due;
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
            $cart->price = $req_cart['price'];
            $cart->skey = "sodaikhana";
            $cart->user_id = 1; 

            $cart->save();
        }

        // admin numbers
        $setting = Setting::find(1);

        // send sms
        $token = "8860182221167214374107f5185b3fd452097c46f974f851619f";
        $message = "A new order come in order ID: ".$order->id." Sodaikhana.com";
        
        $url = "http://api.greenweb.com.bd/api.php";
        
        
        $data= array(
        'to'=> $setting->sms_numbers,
        'message'=>"$message",
        'token'=>"$token"
        ); 

        if($req->terminal == null){
            $ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $smsresult = curl_exec($ch);
        }

        return new OrderResource($order);
    }

    public function update(Request $req, $id){
        $order = Order::find($id);

        $order->status = $req->status;

        $order->update();
        return new OrderResource($order);
    }

    public function payDue(Request $req, $id){
        $order = Order::find($id);

        $order->status = $req->status;
        $order->due -= $req->paid;
        $order->paid += $req->paid;

        $order->update();
        return new OrderResource($order);
    }

    public function delete($id){
        $order = Order::find($id);
        $order->delete();

        return response(['message' => 'Order Deleted Successfuly']);
    }
    
    public function ordersms(Request $req){
        return redirect()->back()->with('message', 'Order SMS send Successfuly');
    }
}
