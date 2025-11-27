<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiBBesar — Reset Password</title>
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
            margin-bottom: 30px;
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
    <h1 class="title">Reset Password</h1>
    <img src="{{ asset('images/logo_wb.png') }}" alt="Logo" class="user-avatar">

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="input">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke="#666" stroke-width="1.2"/>
            </svg>
            <input type="email" name="email" placeholder="Email" value="{{ old('email', $request->email) }}" required autofocus />
        </div>

        <div class="input">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 15a2 2 0 100-4 2 2 0 000 4z" stroke="#666" stroke-width="1.2"/>
                <path d="M4 11v3a8 8 0 0016 0v-3" stroke="#666" stroke-width="1.2"/>
            </svg>
            <input type="password" name="password" placeholder="Password Baru" required />
        </div>

        <div class="input">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 15a2 2 0 100-4 2 2 0 000 4z" stroke="#666" stroke-width="1.2"/>
                <path d="M4 11v3a8 8 0 0016 0v-3" stroke="#666" stroke-width="1.2"/>
            </svg>
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required />
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

        <button class="btn" type="submit">Reset Password</button>
    </form>
</div>
</body>
</html>
