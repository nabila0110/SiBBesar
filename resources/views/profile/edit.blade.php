@extends('layouts.app')

@section('title', 'Edit Profil - SiBBesar')

@push('styles')
<style>
    .profile-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 45px 40px;
        border-radius: 16px;
        margin-bottom: 35px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar-section {
        display: flex;
        align-items: center;
        gap: 28px;
        margin-bottom: 0;
    }

    .profile-avatar-container {
        position: relative;
        flex-shrink: 0;
    }

    .profile-avatar {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .avatar-upload-label {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: #667eea;
        border-radius: 50%;
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 3px solid white;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .avatar-upload-label:hover {
        background: #5568d3;
        transform: scale(1.05);
    }

    .avatar-upload-label i {
        font-size: 16px;
    }

    .avatar-upload-input {
        display: none;
    }

    .profile-info h1 {
        font-size: 32px;
        margin: 0 0 10px 0;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .profile-info p {
        margin: 0;
        opacity: 0.95;
        font-size: 17px;
        font-weight: 400;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 38px 40px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 28px;
        border: 1px solid #f3f4f6;
    }

    .profile-card h3 {
        font-size: 21px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 28px 0;
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 18px;
        border-bottom: 2px solid #f3f4f6;
    }

    .profile-card h3 i {
        color: #667eea;
        font-size: 23px;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 10px;
        font-size: 15px;
    }

    .form-group input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.3s ease;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background-color: #fafafa;
    }

    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        background-color: #ffffff;
    }

    .form-group input::placeholder {
        color: #9ca3af;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .alert {
        padding: 16px 20px;
        border-radius: 10px;
        margin-bottom: 28px;
        font-size: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 500;
    }

    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-success i {
        font-size: 18px;
    }

    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .form-actions {
        display: flex;
        gap: 14px;
        justify-content: flex-end;
        margin-top: 38px;
        padding-top: 28px;
        border-top: 2px solid #f3f4f6;
    }

    .btn {
        padding: 14px 28px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.35);
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: 2px solid #e5e7eb;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
        border-color: #d1d5db;
    }

    .password-info {
        background: #fffbeb;
        border: 2px solid #fde68a;
        color: #92400e;
        padding: 14px 16px;
        border-radius: 10px;
        font-size: 14px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        line-height: 1.6;
    }

    .password-info i {
        margin-top: 2px;
        flex-shrink: 0;
        font-size: 16px;
    }

    @media (max-width: 640px) {
        .profile-container {
            padding: 20px 15px;
        }

        .profile-header {
            padding: 30px 25px;
        }

        .profile-avatar-section {
            flex-direction: column;
            text-align: center;
            gap: 20px;
        }

        .profile-info h1 {
            font-size: 24px;
        }

        .profile-info p {
            font-size: 15px;
        }

        .profile-card {
            padding: 25px 20px;
        }

        .profile-card h3 {
            font-size: 18px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-container">
    <!-- Header -->
    <div class="profile-header">
        <div class="profile-avatar-section">
            <div class="profile-avatar-container">
                <img id="avatarPreview" src="{{ $user->avatar ? asset('images/' . $user->avatar) : asset('images/logo_pt.jpg') }}" 
                     alt="Profile Avatar" class="profile-avatar">
                <label for="avatarInput" class="avatar-upload-label">
                    <i class="fas fa-camera"></i>
                </label>
            </div>
            <div class="profile-info">
                <h1>{{ $user->name }}</h1>
                <p>{{ $user->email }}</p>
            </div>
        </div>
    </div>

    <!-- Status Messages -->
    @if(session('status'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Edit Form -->
    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf

        <!-- Avatar Upload (Hidden) -->
        <input id="avatarInput" type="file" name="avatar" class="avatar-upload-input" accept="image/*">

        <!-- Basic Information -->
        <div class="profile-card">
            <h3>
                <i class="fas fa-user"></i>
                Informasi Dasar
            </h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>
            </div>
        </div>

        <!-- Password Change -->
        <div class="profile-card">
            <h3>
                <i class="fas fa-lock"></i>
                Ubah Password
            </h3>
            
            <div class="password-info">
                <i class="fas fa-info-circle"></i>
                <span>Biarkan kosong jika tidak ingin mengubah password</span>
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" placeholder="Minimal 8 karakter">
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Masukkan ulang password baru">
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Preview avatar on upload
    document.getElementById('avatarInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('avatarPreview').src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush

@endsection
