<?php

namespace App\Http\Controllers;


use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function send()
    {
        Mail::to('test@example.com')->send(
            new SendMail(
                'Test Subject',
                'Hello!This is a test email.',
                false
            )
        );


        return response()->json([
            'status' => true,
            'message' => 'Mail sent successfully',
        ], 200);
    }
}
