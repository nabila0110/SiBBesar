<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiBBesar — Lupa Password</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_wb.png') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 400px;
            text-align: center;
        }
        .title {
            color: #333;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .description {
            color: #555;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
        .input {
            position: relative;
            margin-bottom: 20px;
        }
        .input .icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            color: #666;
        }
        .input input {
            width: 100%;
            padding: 15px 15px 15px 50px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        .input input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
            width: 100%;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .back-link {
            display: block;
            margin-top: 20px;
            font-size: 14px;
        }
        .back-link a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        .back-link a:hover {
            color: #667eea;
            text-decoration: underline;
        }
        .success-message {
            background-color: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            color: #16a34a;
            font-size: 14px;
        }
        .error-message {
            background-color: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            text-align: left;
        }
        .error-message ul {
            margin: 0;
            padding-left: 20px;
            color: #dc2626;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="card">
    <h1 class="title">Lupa Password</h1>
    <img src="{{ asset('images/logo_wb.png') }}" alt="Logo" class="user-avatar">
    
    <p class="description">
        Lupa password Anda? Tidak masalah. Masukkan alamat email Anda dan kami akan mengirimkan link untuk reset password.
    </p>

    @if (session('status'))
        <div class="success-message">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="input">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke="#666" stroke-width="1.2"/>
            </svg>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus />
        </div>

        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button class="btn" type="submit">Kirim Link Reset Password</button>
    </form>

    <div class="back-link">
        <a href="{{ route('login') }}">← Kembali ke Login</a>
    </div>
</div>
</body>
</html>