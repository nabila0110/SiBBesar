@extends('layouts.app')

@section('content')
<h1>Create Journal</h1>
<form method="POST" action="{{ route('journals.store') }}">
    @csrf
    <label>Description</label>
    <input name="description" />
    <button type="submit">Save</button>
</form>
@endsection
