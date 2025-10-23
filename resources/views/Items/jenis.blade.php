@extends('layouts.app')
@section('title', 'Jenis Barang')
@section('content')
<h2>🧩 Jenis Barang</h2>
<p>Data Jenis Barang</p>

<table class="table table-bordered mt-3">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Jenis</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <tr><td>1</td><td>Elektronik</td><td>Barang-barang elektronik</td></tr>
        <tr><td>2</td><td>Perabot</td><td>Barang kebutuhan rumah tangga</td></tr>
    </tbody>
</table>
@endsection
