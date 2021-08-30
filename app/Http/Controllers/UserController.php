<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create($request->all());
        $token = $user->createToken('mySecretToken')->plainTextToken;

        $response = [

            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request){

        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        //getting authenticate user for generating token
        $user = User::where('email', $request->email)->first();

        //check password
       // if (!Hash::check($request->password, $user->password)) {
         //  return response( 'Bad credentials', 401 );
       // }

        return response([
            'User' => $user,
            'Token' => $user->createToken('APi login token')
        ], 200); 
   
    }

    public function logout($id){

        $user = User::find($id);
        
        $user->tokens()->delete();

        return response('Logged out', 200);
    }
}
