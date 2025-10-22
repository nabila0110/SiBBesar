<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiBBesar</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg1: #8e66c7;
            --bg2: #b06ab3;
            --panel: rgba(255,255,255,0.12);
            --panel2: rgba(255,255,255,0.06);
            --accent: #7b76a6;
            --white: #ffffff;
        }
        html,body{height:100%;margin:0;font-family: Nunito, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;}
        body{
            background: linear-gradient(135deg,var(--bg1),var(--bg2));
            display:flex;align-items:center;justify-content:center;
        }
        .card{
            width:420px;max-width:92%;background:var(--panel);border-radius:12px;padding:34px 36px;box-shadow:0 6px 18px rgba(0,0,0,0.12);
            backdrop-filter: blur(6px);
            color:var(--white);
            text-align:center;
        }
        .brand{
            width:80px;height:80px;margin:0 auto 10px;background:transparent;display:block;
        }
        h1.title{font-size:18px;letter-spacing:2px;margin:8px 0 18px;font-weight:700;color:#fff}
        .input{display:flex;align-items:center;background:var(--panel2);border-radius:8px;padding:10px 12px;margin:10px 0}
        .input input{flex:1;border:0;background:transparent;color:#fff;font-size:14px;padding-left:8px;outline:none}
        .btn{display:inline-block;background:var(--accent);color:#111;padding:12px 36px;border-radius:8px;margin-top:18px;border:0;font-weight:700;letter-spacing:2px}
        .icon{width:20px;height:20px;opacity:0.9}
        .small{font-size:13px;opacity:0.9}
        @media (max-width:480px){.card{padding:20px}}
    </style>
</head>
<body>
    @yield('content')
</body>
</html>
