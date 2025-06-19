<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .logo {
            max-width: 150px;
        }
        .otp-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .otp-code {
            font-size: 28px;
            letter-spacing: 5px;
            color: #2563eb;
            font-weight: bold;
            padding: 10px 20px;
            background: white;
            border-radius: 4px;
            display: inline-block;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
        .payment-container {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    text-align: center;
}
.amount {
    font-size: 24px;
    color: #16a34a;
    font-weight: bold;
    margin-bottom: 10px;
}

    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('imgs/logo.png'))) }}" alt="Logo">


        <h2>OTP Verification</h2>
    </div>

    <p>Hello {{ $user->name }},</p>

    <p>You requested a one-time password for your account. Here's your verification code:</p>

    <div class="otp-container">
        <div class="otp-code">{{ $otp }}</div>
        <p>This code will expire at {{ $expiryTime->format('g:i A') }} ({{ $expiryTime->diffForHumans() }}).</p>
    </div>

    <p>If you didn't request this code, please ignore this email or contact our support team.</p>

    <div class="footer">
        <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p>
            <a href="{{ config('app.url') }}">Home</a> |

        </p>
    </div>
</body>
</html>
