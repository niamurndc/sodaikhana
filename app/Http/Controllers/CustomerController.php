<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(){
        return UserResource::collection(User::all());
    }

    public function store(Request $req){
        $req->validate([
            'name' => 'required|string'
        ]);

        do {
            $randomNumber = mt_rand(100000, 999999);
        } while (DB::table('users')->where('ref_code', $randomNumber)->exists());


        $user = new User();

        $user->name = $req->name;
        $user->phone = $req->phone;
        $user->area = $req->area;
        $user->address = $req->address;
        $user->ref_code = rand(10000, 99999);
        $user->token = $randomNumber;
        $user->password = bcrypt($req->password);

        $user->save();
        return new UserResource($user);
    }


    public function show($id){
        return new UserResource(User::findOrFail($id));
    }

    public function search($query){
        return UserResource::collection(User::where('name', 'LIKE', '%'. $query .'%')->orWhere('phone', 'LIKE', '%'. $query .'%')->get());
    }


    public function update(Request $req, $id){
        $req->validate([
            'name' => 'required|string'
        ]);

        $user = User::find($id);

        $user->name = $req->name;
        $user->area = $req->area;
        $user->balance = $req->balance;
        $user->address = $req->address;
        $user->password = $req->password == null ? $user->password : bcrypt($req->password);
        $user->status = $req->status;

        $user->update();
        return new UserResource($user);
    }

    public function delete($id){
        $user = User::find($id);
        $user->delete();

        return response(['message' => 'Deleted Successful']);
    }
}
