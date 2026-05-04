<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerificationMail;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    // Register User
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
            ['guard_name', 'api'],
        ])->first();

        if (! $role) {
            $role = Role::create([
                'name' => 'user',
                'guard_name' => 'api',
            ]);
        }

        // 3. Assign the role to user
        $user->assignRole($role);

        $token = Str::random(64);
        $expiresAt = now()->addHours(24);

        VerificationToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        $verificationUrl = config('app.frontend_url', 'http://localhost:5000') . '/verify-email/' . $token;
        Mail::to($user->email)->send(new EmailVerificationMail($user, $verificationUrl));

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully. Please verify your email.',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', false);

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid email or password',
            ], 401);
        }

        $user = Auth::guard('api')->user();

        if (! $user->email_verified_at) {
            Auth::guard('api')->logout();

            return response()->json([
                'status' => false,
                'message' => 'Please verify your email first',
            ], 403);
        }

        $minutes = $remember ? (60 * 24 * 30) : 60;

        $cookie = Cookie::make('token', $token, $minutes)
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
                'role' => $user->getRoleNames()->first(),
                'image_url' => $user->image_url,
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ],
        ], 200)->withCookie($cookie);
    }

    // LogOut User
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
