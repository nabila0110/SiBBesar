<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SiBBesar')</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg1: #8e66c7;
            --bg2: #b06ab3;
            --panel: rgba(255,255,255,0.12);
            --panel2: rgba(255,255,255,0.06);
            --accent: #7b76a6;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Nunito', sans-serif;
        }

        body {
            background: linear-gradient(135deg, var(--bg1), var(--bg2));
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: var(--panel2);
            border-radius: 12px;
            padding: 30px;
            width: 100%;
            max-width: 420px;
            text-align: center;
            box-sizing: border-box;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .brand {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: block;
        }

        .title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 30px;
        }

        form {
            width: 100%;
            margin: 0 auto;
        }

        .input {
            position: relative;
            margin-bottom: 20px;
            width: 100%;
        }

        .input .icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
        }

        .input input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: none;
            border-radius: 8px;
            background: rgba(255,255,255,0.1);
            color: var(--white);
            font-size: 16px;
            outline: none;
            display: block;
        }

        .input input::placeholder {
            color: rgba(255,255,255,0.7);
        }

        .btn {
            background: var(--accent);
            color: #111;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            display: block;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #9a95cc;
        }

        .small {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    @yield('content')
</body>
</html>
