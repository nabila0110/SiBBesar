@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/akun.css') }}">
@endpush

@section('content')

<div class="container mt-2">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Daftar Akun</h3>
    <div>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#buatAkunModal">+ Buat Akun</button>
    </div>
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table id="akunTable" class="table table-striped table-bordered" style="width:100%">
        <thead class="table-light">
          <tr>
            <th style="width:50px">No</th>
            <th style="width:110px">Kode</th>
            <th>Nama Akun</th>
            <th style="width:120px">Tipe</th>
            <th style="width:220px">Tindakan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($accounts as $account)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>[{{ $account->category->code }}-{{ $account->code }}]</td>
              <td>{{ $account->name }}</td>
              <td>{{ $account->type }}</td>
              <td>
                <button class="btn btn-edit btn-sm me-1 edit-row">Edit</button>
                <button class="btn btn-hapus btn-sm delete-row">Hapus</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Buat Akun -->
<div class="modal fade" id="buatAkunModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formAkun">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Akun</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Kategori Akun:</label>
            <select name="account_category_id" id="kategoriAkun" class="form-select" required>
              <option value="">Pilih Kategori</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" data-code="{{ $category->code }}">{{ $category->code }} - {{ $category->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Kode Akun:</label>
            <input type="text" name="code" id="kodeAkun" class="form-control" placeholder="1100" required>
            <small class="text-muted">Masukkan kode akun saja (tanpa kategori)</small>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Akun:</label>
            <input type="text" name="name" id="namaAkun" class="form-control" placeholder="Contoh: Petty Cash" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Kelompok Akun:</label>
            <select name="group" id="kelompok" class="form-select" required>
              <option value="Assets">Assets</option>
              <option value="Liabilities">Liabilities</option>
              <option value="Equity">Equity</option>
              <option value="Revenue">Revenue</option>
              <option value="Expense">Expense</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Tipe Akun:</label>
            <select name="type" id="tipe" class="form-select" required>
              <option value="asset">Asset</option>
              <option value="liability">Liability</option>
              <option value="equity">Equity</option>
              <option value="revenue">Revenue</option>
              <option value="expense">Expense</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Beban (opsional):</label>
            <input type="text" name="expense_type" id="jenisBeban" class="form-control" placeholder="Beban Kas">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan Akun</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- Custom JS -->
<script src="{{ asset('js/akun.js') }}"></script>
@endsection