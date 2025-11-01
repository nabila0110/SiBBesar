@extends('layouts.app')

@section('title', 'Jenis Barang - SiBBesar')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">📦 Jenis Barang</h3>
        <a href="{{ route('jenis-barang.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Jenis Barang
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Jenis Barang</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($jenisBarangs as $index => $item)
                            <tr>
                                <td class="fw-bold text-muted">{{ $jenisBarangs->firstItem() + $index }}</td>
                                <td>{{ $item->nama_jenis }}</td>
                                <td>
                                    <a href="{{ route('jenis-barang.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('jenis-barang.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Belum ada data jenis barang</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($jenisBarangs->hasPages())
                <nav aria-label="Page navigation" class="mt-4">
                    {{ $jenisBarangs->links('pagination::bootstrap-5') }}
                </nav>
            @endif
        </div>
    </div>
</div>
@endsection