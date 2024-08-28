<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
    <p>Your verification code is:</p>
    <h2>{{ $verificationCode }}</h2>

    <p>Please enter this code on the verification page to complete your registration.</p>

    <p>
        <a href="{{ $verificationUrl }}">Complete your registration by clicking this link.</a>
    </p>
</body>
</html>
