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
    
    /* Search form */
    .search-form {
        max-width: 400px;
    }
    
    .search-form input {
        width: 280px;
    }
    
    .btn-tambah { 
        padding: 0.5rem 1rem !important; 
        font-size: 0.9375rem !important;
        font-weight: 400 !important;
        border-radius: 0.375rem !important;
        white-space: nowrap;
        width: auto !important;
        max-width: fit-content !important;
    }
    
    /* Pagination styling - matching daftar hutang */
    .pagination {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        justify-content: center;
    }
    
    .pagination .page-item {
        margin: 0;
    }
    
    .pagination .page-link {
        color: #495057;
        background-color: #fff;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: all 0.15s ease-in-out;
        min-width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
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
        font-weight: 500;
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
        box-shadow: none;
        outline: none;
    }
  </style>
</head>

<body>
<div class="container my-3">
  <h3 class="mb-3">Data Supplier</h3>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card p-4">
    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
      <form action="{{ route('supplier.index') }}" method="GET" class="d-flex flex-wrap gap-2 search-form">
        <input type="text" name="search" class="form-control" placeholder="Cari nama / email / telepon..." value="{{ $search ?? '' }}">
        <button type="submit" class="btn btn-outline-primary">Cari</button>
      </form>

      <button class="btn btn-primary btn-tambah" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Supplier</button>
    </div>

    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Nama Supplier</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Jumlah Barang</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($suppliers as $index => $supplier)
            <tr>
              <td>{{ $suppliers->firstItem() + $index }}</td>
              <td>{{ $supplier->nama_supplier }}</td>
              <td>{{ $supplier->email ?? '-' }}</td>
              <td>{{ $supplier->telepon ?? '-' }}</td>
              <td>{{ $supplier->alamat ?? '-' }}</td>
              <td>{{ $supplier->barangs_count }} item</td>
              <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $supplier->id }}">Edit</button>
                <button type="button" class="btn btn-danger btn-sm delete-btn" 
                        data-id="{{ $supplier->id }}" 
                        data-nama="{{ $supplier->nama_supplier }}">
                  Hapus
                </button>
                <form id="delete-form-{{ $supplier->id }}" 
                      action="{{ route('supplier.destroy', $supplier->id) }}" 
                      method="POST" 
                      style="display: none;">
                  @csrf
                  @method('DELETE')
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted">Belum ada data supplier</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-3">
      <div class="text-muted">
        Showing {{ $suppliers->firstItem() ?? 0 }} to {{ $suppliers->lastItem() ?? 0 }} of {{ $suppliers->total() }} results
      </div>
      <div>
        {{ $suppliers->appends(['search' => request('search')])->links() }}
      </div>
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
            <label>Nama Supplier <span class="text-danger">*</span></label>
            <input type="text" name="nama_supplier" class="form-control" required maxlength="100">
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" maxlength="50">
          </div>
          <div class="mb-3">
            <label>Telepon</label>
            <input type="text" name="telepon" class="form-control" maxlength="20">
          </div>
          <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" rows="2" maxlength="255"></textarea>
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
            <label>Nama Supplier <span class="text-danger">*</span></label>
            <input type="text" name="nama_supplier" id="editNama" class="form-control" required maxlength="100">
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" id="editEmail" class="form-control" maxlength="50">
          </div>
          <div class="mb-3">
            <label>Telepon</label>
            <input type="text" name="telepon" id="editTelepon" class="form-control" maxlength="20">
          </div>
          <div class="mb-3">
            <label>Alamat</label>
            <textarea name="alamat" id="editAlamat" class="form-control" rows="2" maxlength="255"></textarea>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Fetch data untuk modal edit
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      fetch(`/supplier/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(res => res.json())
        .then(data => {
          document.getElementById('editId').value = data.id;
          document.getElementById('editNama').value = data.nama_supplier;
          document.getElementById('editEmail').value = data.email || '';
          document.getElementById('editTelepon').value = data.telepon || '';
          document.getElementById('editAlamat').value = data.alamat || '';
          document.getElementById('editForm').action = `/supplier/${id}`;
          new bootstrap.Modal(document.getElementById('modalEdit')).show();
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal memuat data supplier'
          });
        });
    });
  });

  // Handle delete dengan SweetAlert2
  document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.dataset.id;
      const nama = this.dataset.nama;
      
      Swal.fire({
        title: 'Hapus Supplier?',
        text: `Yakin ingin menghapus "${nama}"? Barang terkait akan kehilangan data supplier.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById(`delete-form-${id}`).submit();
        }
      });
    });
  });
</script>

</body>
</html>
@endsection
