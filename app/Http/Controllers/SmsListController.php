<?php

namespace App\Http\Controllers;

use App\Http\Resources\SmsListResource;
use App\Models\Order;
use App\Models\SmsList;
use App\Models\User;
use Illuminate\Http\Request;

class SmsListController extends Controller
{
    public function index(){
        return SmsListResource::collection(SmsList::all());
    }

    public function show($id){
        $list = SmsList::find($id);
        return new SmsListResource($list);
    }
    

    public function store(Request $request){
        
        $list = new SmsList();
        $list->title = $request->title;
        $list->save();
        
        return new SmsListResource($list);
    }

    public function delete($id){
        $list = SmsList::find($id);

        foreach($list->items as $item){
            $item->delete();
        }
        $list->delete();

        return response(['message' => 'Deleted Successful']);
    }

    public function send(Request $request, $id){
        $numbers = [];


        if($id == 0){
            $customer = User::where('role', 23314)->get();

            foreach($customer as $item){
                array_push($numbers, $item->phone);
            }

        }else{
            $list = SmsList::find($id);

            foreach($list->items as $item){
                array_push($numbers, $item->phone);
            }
        }

        $numberList = implode(',', $numbers);


        $token = "8860182221167214374107f5185b3fd452097c46f974f851619f";
        $message = $request->text;
        
        $url = "http://api.greenweb.com.bd/api.php";
        
        
        $data= array(
        'to'=> $numberList,
        'message'=>"$message",
        'token'=>"$token"
        ); 

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        
        // //Result
        // echo $smsresult;
        
        // //Error Display
        // echo curl_error($ch);


        return response(['message' => 'Deleted Successful']);
    }

    public function sendOrderSms(Request $request, $id){
        $order = Order::find($id);

        $token = "8860182221167214374107f5185b3fd452097c46f974f851619f";
        $message = $request->text;
        
        $url = "http://api.greenweb.com.bd/api.php";
        
        
        $data= array(
        'to'=> $order->phone,
        'message'=>"$message",
        'token'=>"$token"
        ); 

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $smsresult = curl_exec($ch);
        
        return response(['message' => 'Send Successful']);
    }
}
