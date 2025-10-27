@extends('layouts.app')
@section('title', 'Jenis Barang')
@section('content')
<h1>🧩 Jenis Barang</h1>
<div class="card">
    <a href="#" class="btn">+ Tambah Jenis</a>
    <table>
        <thead><tr><th>ID</th><th>Nama Jenis</th></tr></thead>
        <tbody>
            <tr><td>1</td><td>Elektronik</td></tr>
            <tr><td>2</td><td>Furniture</td></tr>
        </tbody>
    </table>
</div>
@endsection
