@extends('layouts.app')
@section('title', 'Merek Barang')
@section('content')
<h1>🏷️ Merek Barang</h1>
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <a href="{{ route('merk-barang.create') }}" class="btn">+ Tambah Merek</a>
        <form method="GET" action="{{ route('merk-barang.index') }}" style="display:flex;gap:10px;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari merek barang..." style="padding:8px;border:1px solid rgba(255,255,255,0.2);border-radius:4px;background:var(--panel);color:var(--white);">
            <button type="submit" class="btn">Cari</button>
        </form>
    </div>
    <table>
        <thead><tr><th>No</th><th>Nama Merek</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse ($merks as $merk)
            <tr>
                <td>{{ $loop->iteration + ($merks->currentPage() - 1) * $merks->perPage() }}</td>
                <td>{{ $merk->nama_merk }}</td>
                <td>
                    <a href="{{ route('merk-barang.edit', $merk->id) }}" class="btn" style="padding:5px 10px;font-size:13px;">✏️ Update</a>
                    <form action="{{ route('merk-barang.destroy', $merk->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background:#f66;color:white;padding:5px 10px;font-size:13px;" onclick="return confirm('Apakah Anda yakin ingin menghapus?')">🗑️ Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:20px;text-align:center;">
        {{ $merks->appends(request()->query())->links() }}
    </div>
</div>
@endsection
