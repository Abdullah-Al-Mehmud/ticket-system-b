<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //Register User
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()->first(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $role = Role::where([
            ['name', 'user'],
            ['guard_name', 'api']
        ])->first();

        if (!$role) {
            $role = Role::create([
                'name' => 'user',
                'guard_name' => 'api'
            ]);
        }

        // 3. Assign the role to user
        $user->assignRole($role);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password'
            ], 401);
        }

        $user = Auth::guard('api')->user();


        $cookie = Cookie::make('token', $token, 60 * 24 * 30) // 30 দিন
            ->withPath('/')
            ->withHttpOnly(true)
            ->withSameSite('None')
            ->withSecure();

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'token_type' => 'bearer',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->getRoleNames()->first(), // ✅ Spatie Role
            ]
        ], 200)->withCookie($cookie);
    }


    //LogOut User
    public function logout()
    {
        $cookie = Cookie::forget('token');
        Auth::guard('api')->logout();


        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
        ])->withCookie($cookie);
    }
}
