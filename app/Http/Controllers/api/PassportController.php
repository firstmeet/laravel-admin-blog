<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class PassportController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users|max:255',
            'email' => 'required|email',
            'password'=>'required|between:6,20|confirmed',
            'password_confirmation'=>'required'
        ]);

        if ($validator->fails()) {
              return response()->json($validator->errors(),403);
        }
         $user=User::create([
             'name'=>$request->name,
             'email'=>$request->email,
             'password'=>bcrypt($request->password)
         ]);
         return response()->json($user->createToken('group'),200);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password'=>'required|between:6,20',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),403);
        }
         if (Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
             $user=Auth::user();
             return \response()->json($user->createToken('group'),200);
         }else{
             return \response()->json('Unauthorised',401);
         }
    }
    public function getDetails(Request $request)
    {
        $user=Auth::user();
        return \response()->json($user,200);
    }
}
