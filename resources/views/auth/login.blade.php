@extends('layouts.auth')

@section('content')
<div class="card">
    <img src="/logo.png" alt="logo" class="brand" onerror="this.style.display='none'" />
    <h1 class="title">SiBBesar — Login</h1>

    <form method="POST" action="{{ route('login') }}" style="width:100%;max-width:320px;margin:0 auto;">
        @csrf

        <div class="input">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5z" stroke="#fff" stroke-width="1.2"/>
                <path d="M2 22c0-3.866 3.582-7 10-7s10 3.134 10 7" stroke="#fff" stroke-width="1.2"/>
            </svg>
            <input type="text" name="email" placeholder="Nama Anda" value="{{ old('email') }}" required>
        </div>

        <div class="input">
            <svg class="icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 15a2 2 0 100-4 2 2 0 000 4z" stroke="#fff" stroke-width="1.2"/>
                <path d="M4 11v3a8 8 0 0016 0v-3" stroke="#fff" stroke-width="1.2"/>
            </svg>
            <input type="password" name="password" placeholder="Kata Sandi" required>
        </div>

        <button class="btn" type="submit" style="width:100%;margin-top:18px;">LOGIN</button>
    </form>

    <p class="small" style="margin-top:16px;opacity:0.8;">PT Mitra Fajar Kencana</p>
</div>
@endsection
