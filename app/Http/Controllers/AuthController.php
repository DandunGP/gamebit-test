<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registrationSeller(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|confirmed',
        ]);

        $email_check = User::where('email', $request->email)->first();

        if($email_check){
            return response()->json([
                'code' => 409,
                'status' => 'Email already exists',
                'result' => null
            ], 409);
        }

        $userNew = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'seller'
        ]);

        $userData = User::find($userNew->id);

        return response()->json([
            'code' => 200,
            'status' => 'Registration Successfull',
            'result' => $userData
        ],200);
    }

    public function registrationBuyer(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|confirmed',
        ]);

        $email_check = User::where('email', $request->email)->first();

        if($email_check){
            return response()->json([
                'code' => 409,
                'status' => 'Email already exists',
                'result' => null
            ], 409);
        }

        $userNew = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'buyer'
        ]);

        $userData = User::find($userNew->id);

        return response()->json([
            'code' => 200,
            'status' => 'Registration Successfull',
            'result' => $userData
        ],200);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $checkUser = User::where('email', $request->email)->first();

        if(!$checkUser){
            return response()->json([
                'code' => 404,
                'status' => 'Email not registered',
                'result' => null
            ], 404);
        }

        if(!Hash::check($request->password, $checkUser->password)){
            return response()->json([
                'code' => 401,
                'status' => 'Password do not match',
                'result' => null
            ], 401);
        }

        $token = $checkUser->createToken('loginToken')->plainTextToken;

        $checkUser->token = $token;

        return response()->json([
            'code' => 200,
            'status' => 'Login Successfull',
            'result' => $checkUser
        ],200);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return response()->json([
            'code' => 200,
            'status' => 'Logout Successfull',
            'result' => null
        ],200);
    }
}
