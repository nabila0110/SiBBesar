@extends('layouts.app')

@section('title','Preferences')

@section('content')
    <div class="card">
        <h2>Preferences</h2>
        <p>Manage user preferences here.</p>
        <p>User: {{ $user->name ?? 'Guest' }}</p>
    </div>
@endsection
