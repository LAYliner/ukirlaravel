<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode OTP Verifikasi</title>
    <style>
        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            color: #333333;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e1e8ed;
        }
        .header {
            background-color: #885007;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            color: #4b5563;
            margin-top: 0;
            margin-bottom: 24px;
        }
        .otp-container {
            background-color: #f9fafb;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 20px;
            margin: 30px auto;
            max-width: 200px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 5px;
            color: #885007;
            margin: 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            font-size: 12px;
            color: #9ca3af;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sanggar Ukir Tana Paser</h1>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>Terima kasih telah melakukan pendaftaran. Silakan gunakan kode OTP di bawah ini untuk memverifikasi akun Anda:</p>
            <div class="otp-container">
                <div class="otp-code">{{ $otpCode }}</div>
            </div>
            <p>Kode ini hanya berlaku selama <strong>10 menit</strong>. Jangan bagikan kode ini kepada siapapun demi keamanan akun Anda.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Sanggar Ukir Tana Paser. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
