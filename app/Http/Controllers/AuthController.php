<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);
       
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response(['response' => $response], 201);
    }


    public function login (Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
              'message' => "The Email Or Pssword Dosen't Match !"
            ], 401);
        }

        $token =  $user->createToken('myapptoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return response($response, 201);
    }
    public function logout(Request $request) {       
        try {
            // Check if there is an authenticated user
            $user = User::find($request->user_id);
            if (!$user) {
                return response()->json(['message' => 'No user authenticated'], 401);
            }
    
            // Revoke and delete tokens
            $user->tokens->each(function ($token, $key) {
                $token->delete();
            });
    
            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    
    }
}
