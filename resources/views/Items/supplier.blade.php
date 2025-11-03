@extends('layouts.app')
@section('title', 'Supplier Barang')
@section('content')
<h2>🚚 Supplier Barang</h2>
<p>Data Supplier Barang</p>

<table class="table table-hover mt-3">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Supplier</th>
            <th>Alamat</th>
            <th>Telepon</th>
        </tr>
    </thead>
    <tbody>
        <tr><td>1</td><td>PT Sinar Jaya</td><td>Jakarta</td><td>0812-3344-5566</td></tr>
        <tr><td>2</td><td>CV Cahaya Elektronik</td><td>Surabaya</td><td>0821-5566-7788</td></tr>
    </tbody>
</table>
@endsection
