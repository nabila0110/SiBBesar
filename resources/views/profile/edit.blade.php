@extends('layouts.app')

@section('title','Edit Profile')

@section('content')
    <div class="card">
        <h2>Edit Profile</h2>

        @if(session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            <div>
                <label for="name">Name</label>
                <input id="name" name="name" value="{{ old('name', $user->name ?? '') }}">
            </div>
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" value="{{ old('email', $user->email ?? '') }}">
            </div>
            <div>
                <button type="submit">Save</button>
            </div>
        </form>
    </div>
@endsection
