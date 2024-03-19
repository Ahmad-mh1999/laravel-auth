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
            'password' => 'required|min:6|max:10|confirmed',
        ]);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
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
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ],200);
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Logged out successfully',
        ]);
    }

    public function updatetUser(Request $request){
        $data = $request->validate([
            'name' => 'required|srting',
        ]);
        auth()->user()->update([
            'name' => $data['name'],
        ]);
        return response([
            'message' => 'user updated successfully',
            'user' => auth()->user(),
        ]);
    }
}
