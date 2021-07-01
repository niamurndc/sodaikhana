<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        
    }

    public function index(){
        return UserResource::collection(User::all());
    }


    public function show($id){
        return new UserResource(User::findOrFail($id));
    }


    public function update(Request $req, $id){
        $req->validate([
            'name' => 'required|string'
        ]);

        $user = User::find($id);

        $user->name = $req->name;
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
