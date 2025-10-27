<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiBBesar — Login</title>
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
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
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
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="card">
    <img src="/logo.png" alt="logo" class="brand" onerror="this.style.display='none'" />
    <h1 class="title">SiBBesar — Login</h1>

    <form method="POST" action="{{ route('login') }}" style="width:100%;max-width:320px;margin:0 auto;">
        @csrf

        <div class="input">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5z" stroke="#666" stroke-width="1.2"/>
                <path d="M2 22c0-3.866 3.582-7 10-7s10 3.134 10 7" stroke="#666" stroke-width="1.2"/>
            </svg>
            <input type="text" name="email" placeholder="Nama Anda" value="{{ old('email') }}" required>
        </div>

        <div class="input">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 15a2 2 0 100-4 2 2 0 000 4z" stroke="#666" stroke-width="1.2"/>
                <path d="M4 11v3a8 8 0 0016 0v-3" stroke="#666" stroke-width="1.2"/>
            </svg>
            <input type="password" name="password" placeholder="Kata Sandi" required>
        </div>

        <button class="btn" type="submit" style="width:100%;margin-top:18px;">LOGIN</button>
    </form>

    <p class="small">PT Mitra Fajar Kencana</p>
</div>
</body>
</html>

