<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiBBesar — Login</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo_wb.png') }}">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .brand {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            border-radius: 50%;
            object-fit: cover;
        }
        .title {
            color: #333;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 30px;
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
        .small {
            font-size: 14px;
            color: black;
            font-weight: bold;
            margin-top: 20px;
        }
        .user-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
<div class="card">
    <h1 class="title">SiBBesar<h1>
    <img src="{{ asset('images/logo_wb.png') }}" alt="User Avatar" class="user-avatar">

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5z" stroke="#666" stroke-width="1.2"/>
                <path d="M2 22c0-3.866 3.582-7 10-7s10 3.134 10 7" stroke="#666" stroke-width="1.2"/>
            </svg>
            <input type="name" name="name" placeholder="Name" value="{{ old('name') }}" required autocomplete="email" autofocus />
        </div>

        <div class="input">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 15a2 2 0 100-4 2 2 0 000 4z" stroke="#666" stroke-width="1.2"/>
                <path d="M4 11v3a8 8 0 0016 0v-3" stroke="#666" stroke-width="1.2"/>
            </svg>
            <input type="password" name="password" placeholder="Kata Sandi" required autocomplete="current-password" />
        </div>

        @if ($errors->any())
            <div style="background-color: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 8px; padding: 12px; margin-bottom: 20px; text-align: left;">
                <ul style="margin: 0; padding-left: 20px; color: #dc2626; font-size: 14px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button class="btn" type="submit">LOGIN</button>
    </form>

    <p class="small">PT. MITRA FAJAR KENCANA</p>
</div>
</body>
</html>
