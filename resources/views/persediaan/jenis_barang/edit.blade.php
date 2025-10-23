@extends('layouts.app')
@section('title', 'Edit Jenis Barang')
@section('content')
<h1>🧩 Edit Jenis Barang</h1>
<div class="card">
    <form action="{{ route('jenis-barang.update', $jenisBarang->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom:15px;">
            <label for="nama_jenis" style="display:block;margin-bottom:5px;">Jenis Barang</label>
            <input type="text" id="nama_jenis" name="nama_jenis" value="{{ $jenisBarang->nama_jenis }}" required style="width:100%;padding:8px;border:1px solid rgba(255,255,255,0.2);border-radius:4px;background:var(--panel);color:var(--white);">
        </div>
        <button type="submit" class="btn">Update</button>
        <a href="{{ route('jenis-barang.index') }}" class="btn" style="background:#666;">Batal</a>
    </form>
</div>
@endsection
