@extends('layouts.app')
@section('title', 'Edit Supplier Barang')
@section('content')
<h1>🚚 Edit Supplier Barang</h1>
<div class="card">
    <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div style="margin-bottom:15px;">
            <label for="kode_supplier" style="display:block;margin-bottom:5px;">Kode Supplier:</label>
            <input type="text" id="kode_supplier" name="kode_supplier" value="{{ $supplier->kode_supplier }}" required style="width:100%;padding:8px;border:1px solid rgba(255,255,255,0.2);border-radius:4px;background:var(--panel);color:var(--white);">
        </div>
        <div style="margin-bottom:15px;">
            <label for="nama_supplier" style="display:block;margin-bottom:5px;">Nama Supplier:</label>
            <input type="text" id="nama_supplier" name="nama_supplier" value="{{ $supplier->nama_supplier }}" required style="width:100%;padding:8px;border:1px solid rgba(255,255,255,0.2);border-radius:4px;background:var(--panel);color:var(--white);">
        </div>
        <button type="submit" class="btn">Update</button>
        <a href="{{ route('supplier.index') }}" class="btn" style="background:#666;">Batal</a>
    </form>
</div>
@endsection
