@extends('layouts.app')

@section('content')
<h1>Journal {{ $journal->journal_no ?? '' }}</h1>
<p>{{ $journal->description ?? '' }}</p>
@if(isset($journal->details) && count($journal->details))
	<ul>
	@foreach($journal->details as $d)
		<li>{{ $d->description }}</li>
	@endforeach
	</ul>
@endif
@endsection
