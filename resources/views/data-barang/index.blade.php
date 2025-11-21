@extends('layouts.app')

@section('title', 'Jenis Barang - SiBBesar')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Barang</title>
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
  <h3 class="mb-4">Data Barang</h3>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card p-4">
    <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
      <!-- 🔍 Form Pencarian -->
      <form action="{{ route('barang.index') }}" method="GET" class="d-flex flex-wrap gap-2">
        <input type="text" name="search" class="form-control" placeholder="Cari nama / kode barang..." value="{{ $keyword ?? '' }}">
        <button type="submit" class="btn btn-outline-primary">Cari</button>
      </form>

      <!-- ➕ Tambah Barang -->
      <button class="btn btn-primary btn-tambah" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah Barang</button>
    </div>

    <!-- 📋 Tabel Data Barang -->
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Supplier</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($barang as $index => $b)
            <tr>
              <td>{{ $barang->firstItem() + $index }}</td>
              <td>{{ $b->kode }}</td>
              <td>{{ $b->nama }}</td>
              <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
              <td>{{ $b->stok }}</td>
              <td>{{ $b->supplier->nama_supplier ?? '-' }}</td>
              <td>
                <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $b->id }}">Edit</button>
                <form action="{{ route('barang.destroy', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus barang ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-danger btn-sm">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center text-muted">Belum ada data barang</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
      {{ $barang->appends(['search' => $keyword])->links() }}
    </div>
  </div>
</div>

<!-- 🧩 Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('barang.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2">
            <label>Kode Barang</label>
            <input type="text" name="kode" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Nama Barang</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Supplier (Opsional)</label>
            <select name="supplier_id" class="form-control">
              <option value="">-- Pilih Supplier --</option>
              @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
              @endforeach
            </select>
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
          <h5 class="modal-title">Edit Barang</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editId">
          <div class="mb-2">
            <label>Kode Barang</label>
            <input type="text" name="kode" id="editKode" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Nama Barang</label>
            <input type="text" name="nama" id="editNama" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Harga</label>
            <input type="number" name="harga" id="editHarga" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Stok</label>
            <input type="number" name="stok" id="editStok" class="form-control" required>
          </div>
          <div class="mb-2">
            <label>Supplier (Opsional)</label>
            <select name="supplier_id" id="editSupplierId" class="form-control">
              <option value="">-- Pilih Supplier --</option>
              @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->nama_supplier }}</option>
              @endforeach
            </select>
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
<script>
  // Fetch data untuk modal edit
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      fetch(/barang/${id}/edit)
        .then(res => res.json())
        .then(data => {
          document.getElementById('editId').value = data.id;
          document.getElementById('editKode').value = data.kode;
          document.getElementById('editNama').value = data.nama;
          document.getElementById('editHarga').value = data.harga;
          document.getElementById('editStok').value = data.stok;
          document.getElementById('editSupplierId').value = data.supplier_id || '';
          document.getElementById('editForm').action = /barang/${id};
          new bootstrap.Modal(document.getElementById('modalEdit')).show();
        });
    });
  });
</script>

</body>
</html>
@endsection