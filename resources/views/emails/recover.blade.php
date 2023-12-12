<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #333;
        }

        h4 {
            color: #555;
        }

        p {
            color: #777;
        }

        .footer-recover {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
        }

        .footer-recover h5 {
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <h3>Hi {{ $mailData['name'] }},</h3>
        <p>We received a request to reset your password. If you didn't make this request, you can safely ignore this email.</p>
        <p>To reset your password, click on the following link:</p>
        <a href="{{ $mailData['resetLink'] }}" style="display: inline-block; margin-top: 10px; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Reset Password</a>
        <p>If the above link doesn't work, copy and paste the following URL into your browser:</p>
        <p>{{ $mailData['resetLink'] }}</p>
        <p>This link will expire in {{ $mailData['expirationTime'] }} minutes for security reasons.</p>
        <p>If you didn't request a password reset or have any questions, please contact our support team.</p>
        <div class="footer-recover">
            <h5>Best Regards,<br> Your App Team</h5>
        </div>
    </div>
</body>

</html>
