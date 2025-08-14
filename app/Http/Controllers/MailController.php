<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send()
    {
        $to = 'example@example.com';
        $sub = 'Test Email';
        $msg = 'Hello! This is a test message.';

        Mail::to($to)->send(new WelcomeEmail($sub, $msg));


        return response()->json([
            'status' => true,
            'message' => 'Mail sent successfully',
        ], 200);
    }
}
