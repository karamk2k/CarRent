<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f5f5f5;
            padding: 0;
        }
        .email-container {
            background: #fff;
            margin: 20px auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .header {
            background-color: #2563eb;
            padding: 30px 20px;
            text-align: center;
            color: white;
        }
        .logo {
            max-width: 180px;
            height: auto;
        }
        .content {
            padding: 30px;
        }
        .payment-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            border-left: 4px solid #2563eb;
        }
        .amount {
            font-size: 28px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 20px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: bold;
            width: 120px;
            color: #666;
        }
        .detail-value {
            flex: 1;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 15px 0;
            font-weight: 500;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            padding: 20px;
            background: #f5f5f5;
        }
        .footer a {
            color: #2563eb;
            text-decoration: none;
            margin: 0 10px;
        }
        @media only screen and (max-width: 600px) {
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                width: auto;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('imgs/logo.png'))) }}" alt="{{ config('app.name') }}" class="logo">
        <h2>Payment Confirmation</h2>
    </div>

    <div class="content">
        <p>Dear {{ $user->name }},</p>

        <p>Thank you for your payment. Your transaction has been completed successfully. Below are the details of your payment:</p>

        <div class="payment-details">
            <div class="amount">{{ number_format($amount, 2) }} JD</div>

            <div class="detail-row">
                <div class="detail-label">Description:</div>
                <div class="detail-value">{{ $carname }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Date & Time:</div>
                <div class="detail-value">{{ $time->format('F j, Y \a\t g:i A') }}</div>
            </div>
        </div>

        <p>A receipt has been generated and is available in your account. You can also download it using the link below:</p>

        <p style="text-align: center;">
            <a href="{{ route('user.history') }}" class="button">View Receipt</a>
        </p>

        <p>If you have any questions about this payment, please don't hesitate to contact our support team.</p>

        <p>Best regards,<br>The {{ config('app.name') }} Team</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p>
            <a href="{{ config('app.url') }}">Home</a> |
            <a href="{{ route('user.history') }}">View History</a>
        </p>
    </div>
</div>
</body>
</html>
