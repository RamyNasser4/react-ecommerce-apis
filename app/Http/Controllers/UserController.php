<?php

namespace App\Http\Controllers;

use App\Http\Requests\createUserRequest;
use App\Http\Requests\editUserRequest;
use App\Http\Requests\signinRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function signup(createUserRequest $request){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id = 2;
        $user->save();
        $token = $user->createToken('myapptoken')->plainTextToken;
        $role = Role::find($user->role_id);
        $response = [
            'user' => $user,
            'role' => $role->role,
            'token' => $token
        ];
        return response($response,201);
    }
    public function signin(signinRequest $request){
        $user = User::where('email',$request->email)->first();
        if(!$user || !Hash::check($request->password,$user->password)){
            return response([
                'message' => 'Bad Credentials',401
            ]);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $role = Role::find($user->role_id);
        $response = [
            'user' => $user,
            'role' => $role->role,
            'token' => $token
        ];
        return response($response,201);
    }
    public function signout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
    public function user($id){
        $user = User::find($id);
        if(!str_starts_with($user->profile_img,"http")){
            $user->profile_img = "data:image/png;base64,". base64_encode(Storage::get("profile_img/" .$user->profile_img));
        }
        if(!str_starts_with($user->cover_img,"http")){
            $user->cover_img = "data:image/png;base64,". base64_encode(Storage::get("cover_img/" .$user->cover_img));
        }
        return response($user,201);
    }
    public function edit($id,editUserRequest $request){
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->phone_number = "+".$request->phone_number;
        if($request->profile_img){
        $profilepath = Storage::putFile('profile_img',$request->profile_img);
        $user->profile_img = explode("/",$profilepath)[1];
        }
        if($request->cover_img){
        $coverpath = Storage::putFile('cover_img',$request->cover_img);
        $user->cover_img = explode("/",$coverpath)[1];
        }
        $user->save();
        return response($user,201);
    }
    public function getUserCount(){
        $users = User::all();
        return response(sizeof($users),201);
    }
    
}
