@extends('layouts.app')
@section('title', 'Merek Barang')
@section('content')
<h2>🏷️ Merek Barang</h2>
<p>Data Merek Barang</p>

<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Merek</th>
            <th>Asal Negara</th>
        </tr>
    </thead>
    <tbody>
        <tr><td>1</td><td>Samsung</td><td>Korea</td></tr>
        <tr><td>2</td><td>LG</td><td>Korea</td></tr>
        <tr><td>3</td><td>Polytron</td><td>Indonesia</td></tr>
    </tbody>
</table>
@endsection
