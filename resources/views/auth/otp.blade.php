<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #4e73df, #1cc88a);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            width: 350px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        h2 {
            margin-bottom: 10px;
        }

        p {
            color: gray;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 18px;
            letter-spacing: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            border: none;
            border-radius: 8px;
            background: #4e73df;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #2e59d9;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-top: 10px;
        }

    </style>
</head>
<body>

<div class="card">
    <h2>Verifikasi OTP</h2>
    <p>Masukkan kode OTP yang dikirim ke email kamu</p>

    @if ($errors->any())
        <div class="error" style="color: red; font-size: 13px; margin-top: 10px; text-align: left;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if (session('error'))
        <div class="error" style="color: red; font-size: 13px; margin-top: 10px;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <input type="text" name="otp" maxlength="6" placeholder="______" required autofocus>

        <button type="submit">Verifikasi</button>
    </form>
</div>

</body>
</html>