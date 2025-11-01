@extends('layouts.app')

@section('title', 'Edit Profil - SiBBesar')

@push('styles')
<style>
    .profile-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 40px;
        border-radius: 16px;
        margin-bottom: 32px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar-section {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-bottom: 24px;
    }

    .profile-avatar-container {
        position: relative;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .avatar-upload-label {
        position: absolute;
        bottom: 0;
        right: 0;
        background: #667eea;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        border: 3px solid white;
        transition: all 0.3s ease;
    }

    .avatar-upload-label:hover {
        background: #5568d3;
        transform: scale(1.05);
    }

    .avatar-upload-input {
        display: none;
    }

    .profile-info h1 {
        font-size: 28px;
        margin: 0 0 8px 0;
        font-weight: 700;
    }

    .profile-info p {
        margin: 0;
        opacity: 0.9;
        font-size: 16px;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
    }

    .profile-card h3 {
        font-size: 20px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 24px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .profile-card h3 i {
        color: #667eea;
        font-size: 22px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .form-group input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }

    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background-color: #f8f9ff;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .alert {
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-success i {
        font-size: 16px;
    }

    .alert-error {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    .password-info {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        color: #92400e;
        padding: 12px 14px;
        border-radius: 8px;
        font-size: 13px;
        margin-bottom: 16px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .password-info i {
        margin-top: 2px;
        flex-shrink: 0;
    }

    @media (max-width: 640px) {
        .profile-header {
            padding: 24px;
        }

        .profile-avatar-section {
            flex-direction: column;
            text-align: center;
        }

        .profile-info h1 {
            font-size: 22px;
        }

        .profile-card {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
            justify-content: stretch;
        }

        .btn {
            width: 100%;
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
