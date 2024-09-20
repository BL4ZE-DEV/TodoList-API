<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|unique:users|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'nickname' => $request->nickname,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User registered successfully',
            'token' => $token,
            'user' => $user
        ], 201);
    }



    public function login(Request $request)
    {
        $request->validate([
            'email' =>'required|email',
            'password' => 'required'
        ]);
    
        $credentials = $request->only('email', 'password');
    
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid email or password'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['message' => 'Could not create token'], 500);
        }
    
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'data' => Auth::user()
        ]);
    }
}
