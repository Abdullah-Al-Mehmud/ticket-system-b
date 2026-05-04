<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerificationMail;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, $token)
    {
        $verificationToken = VerificationToken::where('token', $token)->first();

        if (! $verificationToken) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid verification token',
            ], 404);
        }

        if ($verificationToken->isExpired()) {
            return response()->json([
                'status' => false,
                'message' => 'Verification token has expired',
            ], 400);
        }

        $user = $verificationToken->user;
        $user->email_verified_at = now();
        $user->save();

        $verificationToken->delete();

        return response()->json([
            'status' => true,
            'message' => 'Email verified successfully',
        ], 200);
    }

    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }

        if ($user->email_verified_at) {
            return response()->json([
                'status' => false,
                'message' => 'Email is already verified',
            ], 400);
        }

        VerificationToken::where('user_id', $user->id)->delete();

        $token = Str::random(64);
        $expiresAt = now()->addHours(24);

        VerificationToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        $verificationUrl = config('app.frontend_url', 'http://localhost:3000').'/verify-email/'.$token;

        Mail::to($user->email)->send(new EmailVerificationMail($user, $verificationUrl));

        return response()->json([
            'status' => true,
            'message' => 'Verification email sent successfully',
        ], 200);
    }
}
