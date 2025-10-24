@extends('layouts.app')
@section('title', 'Tambah Jenis Barang')
@section('content')
<h1>🧩 Tambah Jenis Barang</h1>

<div class="card">
    <form action="{{ route('jenis_barang.store') }}" method="POST">
        @csrf

        <div style="margin-bottom:15px;">
            <label for="nama_jenis" style="display:block;margin-bottom:5px;">Jenis Barang</label>
            <input type="text" id="nama_jenis" name="nama_jenis" required
                style="width:100%;padding:8px;border:1px solid rgba(255,255,255,0.2);
                border-radius:4px;background:var(--panel);color:var(--white);">
        </div>

        <div style="display:flex;gap:10px;">
            <button type="submit"
                style="flex:1;padding:10px 20px;border:none;border-radius:6px;
                background:#28a745;color:white;font-weight:bold;cursor:pointer;
                transition:0.3s;">
                Simpan
            </button>

            <a href="{{ route('jenis_barang.index') }}"
                style="flex:1;text-align:center;padding:10px 20px;border:none;
                border-radius:6px;background:#dc3545;color:white;text-decoration:none;
                font-weight:bold;display:inline-block;cursor:pointer;transition:0.3s;">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
