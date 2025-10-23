@extends('layouts.app')
@section('title', 'Jenis Barang')
@section('content')
<h1>🧩 Jenis Barang</h1>
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <a href="{{ route('jenis-barang.create') }}" class="btn">+ Tambah Jenis Barang</a>
        <form method="GET" action="{{ route('jenis-barang.index') }}" style="display:flex;gap:10px;">
            <input type="text" name="search" value="{{ $search }}" placeholder="Cari jenis barang..." style="padding:8px;border:1px solid rgba(255,255,255,0.2);border-radius:4px;background:var(--panel);color:var(--white);">
            <button type="submit" class="btn">Cari</button>
        </form>
    </div>
    <table>
        <thead><tr><th>No</th><th>Nama Jenis</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse ($jenisBarangs as $jenis)
            <tr>
                <td>{{ $loop->iteration + ($jenisBarangs->currentPage() - 1) * $jenisBarangs->perPage() }}</td>
                <td>{{ $jenis->nama_jenis }}</td>
                <td>
                    <a href="{{ route('jenis-barang.edit', $jenis->id) }}" class="btn" style="padding:5px 10px;font-size:13px;">✏️ Update</a>
                    <form action="{{ route('jenis-barang.destroy', $jenis->id) }}" method="POST" style="display:inline;">
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
        {{ $jenisBarangs->appends(request()->query())->links() }}
    </div>
</div>
@endsection
