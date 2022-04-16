<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use \App\Models\User;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function user(Request $request) {
        return response(Auth::user());
    }

    //register user
    public function register (Request $request){
        return $user = User::create([
            'username' => $request -> input('username'),
            'email' => $request-> input('email'),
            'password' =>Hash::make($request-> input('passsword')),
        ]);
     }


    public function login(Request $request){
        if(!Auth::attempt([ 'email'=>$request-> input('email'),'password' => $request-> input('pasword')])){
            return response([
                'message' => 'Invalid credetials'
            ], Response::HTTP_UNAUTHORIZED);

        }

        $user = Auth::user();
        $token = $request->user()->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24);

        return response([
            'message' => $token,

        ])->withCookie($cookie);

}

    public function logout() {
        $cookie = Cookie::forget('jwt');
        return response([
            'message' =>'Successfully Logged out',
        ])->withCookie($cookie);
    }


}
