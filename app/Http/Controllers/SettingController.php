<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    

    public function index(){
        $setting = Setting::find(1);
        return response(['data' => $setting]);
    }
    

    public function update(Request $req){
        
        $setting = Setting::find(1);

        $setting->ship = $req->ship;
        $setting->reg_point = $req->reg_point;
        $setting->sms_numbers = $req->sms_numbers;

        $setting->update();
        
        return response(['message' => 'Updated successful']);
    }
}
