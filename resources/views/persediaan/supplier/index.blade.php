@extends('layouts.app')
@section('title', 'Supplier Barang')
@section('content')
<h1>🚚 Supplier Barang</h1>
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <a href="{{ route('supplier.create') }}" class="btn">+ Tambah Supplier</a>
        <form method="GET" action="{{ route('supplier.index') }}" style="display:flex;gap:10px;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari supplier..." style="padding:8px;border:1px solid rgba(255,255,255,0.2);border-radius:4px;background:var(--panel);color:var(--white);">
            <button type="submit" class="btn">Cari</button>
        </form>
    </div>
    <table>
        <thead><tr><th>No</th><th>Kode Supplier</th><th>Nama Supplier</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse ($suppliers as $supplier)
            <tr>
                <td>{{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}</td>
                <td>{{ $supplier->kode_supplier }}</td>
                <td>{{ $supplier->nama_supplier }}</td>
                <td>
                    <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn" style="padding:5px 10px;font-size:13px;">✏️ Update</a>
                    <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background:#f66;color:white;padding:5px 10px;font-size:13px;" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">🗑️ Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:20px;text-align:center;">
        {{ $suppliers->appends(request()->query())->links() }}
    </div>
</div>
@endsection
