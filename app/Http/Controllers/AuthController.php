<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $data = $request->validate([
            'name' => 'string|required',
            'phone' => 'numeric|required|unique:users',
            'password' => 'string|required|min:6',
            'ref_code' => 'nullable|integer',
        ]);

        if($request->ref_code != null){
            $referral = User::where('ref_code', $request->ref_code)->first();
            $ref_id = $referral->id;
        }else{
            $ref_id = null;
        }

        do {
            $randomNumber = mt_rand(100000, 999999);
        } while (DB::table('users')->where('ref_code', $randomNumber)->exists());

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
            'token' => rand(10000, 99999),
            'ref_code' => $randomNumber,
            'referral_id' => $ref_id,
        ]);

        if($user){
            $this->send_sms($user->phone, $user->token);
        }

        $token = $user->createToken('apptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response);
    }

    public function varify(Request $request){
        if(auth()->user()->token == $request->code){
            $user = User::find(auth()->user()->id);

            $user->status = 1;
            $user->token = null;
            $user->update();

            $setting = Setting::find(1);
            
            if($user->referral_id != null){
                $referral = User::find($user->referral_id);
                $referral->point += $setting->reg_point;
                $referral->update();
            }
            
            return response(['message' => 'Your profile is varified']);
        }else{
            return response(['error' => 'Your code is not correct']);
        }

    }

    public function sendcode(){
        if(auth()->user()->token == null){
            $user = User::find(auth()->user()->id);

            $user->token = rand(10000, 99999);
            $user->update();

            $this->send_sms(auth()->user()->phone, $user->token);
            
            return response(['message' => 'Your code is send']);
        }else{
            $this->send_sms(auth()->user()->phone, auth()->user()->token);

            return response(['message' => 'Your code is send']);
        }
    }

    public function login(Request $request){
        $data = $request->validate([
            'phone' => 'numeric|required',
            'password' => 'string|required|min:6',
        ]);

        $user = User::where('phone', $data['phone'])->first();

        if(!$user || !Hash::check($data['password'], $user->password)){
            return response(['message' => 'Bad credentials']);
        }

        $token = $user->createToken('apptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response);
    }

    public function forgot(Request $request){
        $user = User::where('phone', $request->phone)->first();

        if(!$user){
            return response(['error' => 'Phone number is not correct']);
        }else{
            $user->token = rand(10000, 99999);
            $user->update();

            $this->send_sms($user->phone, $user->token);

            return response(['message' => 'Code is send']);
        }
    }

    public function getpassword(Request $request){
        $user = User::where('token', $request->code)->first();

        if(!$user){
            return response(['error' => 'Phone number is not correct']);
        }else{
            $user->token = null;
            $user->password = bcrypt($request->password);
            $user->update();

            return response(['message' => 'change password']);
        }
    }

    public function profile(){
        return response(auth()->user());
    }

    public function update(Request $request){
        $id = auth()->user()->id;
        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->password = $request->password == null ? $user->password : bcrypt($request->password);

        $user->update();
        return response($user);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response(['message' => 'Your are logged out']);
    }

    public function send_sms($number, $text){
        $token = "8860182221167214374107f5185b3fd452097c46f974f851619f";
        $message = 'Your OTP code for Sodaikhana '.$text;
        
        $url = "http://api.greenweb.com.bd/api.php";
        
        
        $data= array(
        'to'=> $number,
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
    }
}
