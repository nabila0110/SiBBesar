@extends('layouts.app')
@section('title', 'Edit Merek Barang')
@section('content')
<h1>🏷️ Edit Merek Barang</h1>
<div class="card">
    <form action="{{ route('merk-barang.update', $merkBarang->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom:15px;">
            <label for="nama_merk" style="display:block;margin-bottom:5px;">Nama Merek:</label>
            <input type="text" id="nama_merk" name="nama_merk" value="{{ $merkBarang->nama_merk }}" required style="width:100%;padding:8px;border:1px solid rgba(255,255,255,0.2);border-radius:4px;background:var(--panel);color:var(--white);">
        </div>
        <button type="submit" class="btn">Update</button>
        <a href="{{ route('merk-barang.index') }}" class="btn" style="background:#666;">Batal</a>
    </form>
</div>
@endsection
