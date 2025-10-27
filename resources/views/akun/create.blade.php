@extends('layouts.app')

@section('content')
<h1>Create Account</h1>
<form method="POST" action="{{ route('accounts.store') }}">
    @csrf
    <label>Code</label>
    <input name="code" />
    <label>Name</label>
    <input name="name" />
    <button type="submit">Save</button>
</form>
@endsection
