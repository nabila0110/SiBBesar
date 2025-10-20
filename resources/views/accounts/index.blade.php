@extends('layouts.app')

@section('content')
<h1>Accounts Index</h1>
@if(isset($accounts))
<ul>
@foreach($accounts as $a)
<li>{{ $a->name }} ({{ $a->code }})</li>
@endforeach
</ul>
@endif
@endsection
