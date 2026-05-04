<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h1>Verify Your Email Address</h1>
    <p>Hello {{ $user->name }},</p>
    <p>Thank you for registering. Please verify your email address by clicking the button below:</p>
    <a href="{{ $verificationUrl }}" style="background-color: #4F46E5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">
        Verify Email
    </a>
    <p>If you didn't create an account, you can ignore this email.</p>
    <p>This link will expire in 24 hours.</p>
</body>
</html>