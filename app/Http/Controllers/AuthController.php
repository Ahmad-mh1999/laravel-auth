<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registerUser(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:10',
        ]);
        
        
    $input = $request->all();
    $input['password'] = bcrypt($input['password']);
    $user = User::create($input);
    
    return response([
        'user' => $user,
        'token' => $user->createToken('MyApp')->accessToken
    ],200);

    }
    public function loginUser(Request $request){

        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($data)) {
            return response([
                'message' => 'Invalid credentials',
            ],403);
        }
        return response([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('MyApp')->accessToken
        ],200);
    }
}
