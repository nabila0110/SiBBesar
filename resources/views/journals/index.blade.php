@extends('layouts.app')

@section('content')
<h1>Journals</h1>
@if(isset($journals))
<ul>
@foreach($journals as $j)
<li>{{ $j->journal_no }} - {{ $j->description }}</li>
@endforeach
</ul>
@endif
@endsection
