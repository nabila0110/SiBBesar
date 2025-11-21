@extends('layouts.app')

@section('title', 'Data Supplier - SiBBesar')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Supplier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #eef3f8; font-family: 'Segoe UI', sans-serif; }
    .card { border-radius: 15px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); background: #fff; }
    h3 { font-weight: 700; }
    table td, table th { vertical-align: middle; }
    .btn-tambah { padding: 0.25rem 0.5rem !important; font-size: 0.813rem !important; line-height: 1.2 !important; height: auto !important; }
    
    /* Pagination styling */
    .pagination {
        margin: 0;
        gap: 0.25rem;
    }
    
    .pagination .page-item {
        margin: 0 2px;
    }
    
    .pagination .page-link {
        color: #6c757d;
        background-color: #fff;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: all 0.15s ease-in-out;
    }
    
    .pagination .page-link:hover {
        color: #0056b3;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
        z-index: 3;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .pagination .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: none;
    }
  </style>
</head>

<body>
<div class="container my-5">
  <h3 class="mb-4">🏭 Data Supplier</h3>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card p-4">
    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
      <!-- 🔍 Form Pencarian -->
      <form action="{{ route('supplier.index') }}" method="GET" class="d-flex flex-wrap gap-2">
        <input type="text" name="search" class="form-control" placeholder="Cari nama / kode supplier..." value="{{ $search ?? '' }}">
        <button type="submit" class="btn btn-outline-primary">Cari</button>
      </form>

      <!-- ➕ Tambah Supplier -->
      <button class="btn btn-primary btn-tambah" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Supplier</button>
    </div>

    <!-- 📋 Tabel Data Supplier -->
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Kode Supplier</th>
            <th>Nama Supplier</th>
            <th>Jumlah Barang</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($suppliers as $index => $supplier)
            <tr>
              <td>{{ $suppliers->firstItem() + $index }}</td>
              <td>{{ $supplier->kode_supplier }}</td>
              <td>{{ $supplier->nama_supplier }}</td>
              <td>{{ $supplier->barangs_count }} item</td>
              <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $supplier->id }}">Edit</button>
                <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus supplier ini? Barang terkait akan kehilangan data supplier.')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-sm">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted">Belum ada data supplier</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
      {{ $suppliers->appends(['search' => $search])->links() }}
    </div>
  </div>
</div>

<!-- 🧩 Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('supplier.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Supplier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Kode Supplier</label>
            <input type="text" name="kode_supplier" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Nama Supplier</label>
            <input type="text" name="nama_supplier" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 🧩 Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Supplier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editId">
          <div class="mb-3">
            <label>Kode Supplier</label>
            <input type="text" name="kode_supplier" id="editKode" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Nama Supplier</label>
            <input type="text" name="nama_supplier" id="editNama" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<p class="text-center text-muted small mt-3">Powered by Rana</p>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Fetch data untuk modal edit
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      fetch(`/supplier/${id}/edit`)
        .then(res => res.json())
        .then(data => {
          document.getElementById('editId').value = data.id;
          document.getElementById('editKode').value = data.kode_supplier;
          document.getElementById('editNama').value = data.nama_supplier;
          document.getElementById('editForm').action = `/supplier/${id}`;
          new bootstrap.Modal(document.getElementById('modalEdit')).show();
        });
    });
  });
</script>

</body>
</html>
@endsection
