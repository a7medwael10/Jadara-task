<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F8F8F8;
            text-align: left;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 25px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .header {
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 2px solid #E8EBF0;
        }

        .logo {
            max-width: 130px;
            height: auto;
            margin-bottom: 10px;
        }

        .content {
            padding: 25px 15px;
            text-align: center;
        }

        .otp-box {
            background-color: #E8EBF0;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin: 20px 0;
        }

        .otp-code {
            font-size: 34px;
            font-weight: bold;
            color: #295163;
            letter-spacing: 6px;
        }

        .info {
            color: #4D96B9;
            margin: 10px 0;
            font-size: 16px;
        }

        .warning {
            margin-top: 20px;
            font-size: 14px;
            color: #a94442;
            background-color: #fcebea;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 6px;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #E8EBF0;
            color: #777;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2 style="color: #295163;">Password Reset Request</h2>
    </div>

    <div class="content">
        <p class="info">Hello,</p>
        <p>You requested to reset your account password.</p>
        <p>Please use the verification code below:</p>

        <div class="otp-box">
            <p style="margin-bottom: 10px;">Verification Code:</p>
            <div class="otp-code">{{ $code }}</div>
        </div>

        <p class="info">This code is valid for <strong>10 minutes</strong>.</p>

        <div class="warning">
            ⚠️ Do not share this code with anyone.<br>
            Our support team will never ask for it.
        </div>
    </div>

    <div class="footer">
        <p>This is an automated message. Please do not reply.</p>
        <p>&copy; {{ date('Y') }} All rights reserved.</p>
    </div>
</div>
</body>
</html>
