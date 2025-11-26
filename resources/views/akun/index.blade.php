@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/akun.css') }}">
@endpush

@section('content')

<div class="container mt-2">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold">Daftar Akun</h3>
    <div>
      <a href="{{ route('akun.kategori') }}" class="btn btn-info me-2">📁 Kelola Kategori</a>
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
            <th style="width:150px">Kategori</th>
            <th style="width:120px">Tipe</th>
            <th style="width:220px">Tindakan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($accounts as $account)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>[{{ $account->category->code ?? '' }}-{{ $account->code }}]</td>
              <td>{{ $account->name }}</td>
              <td>{{ $account->category->name ?? '-' }}</td>
              <td>
                <span class="badge bg-{{ 
                  $account->type == 'asset' ? 'primary' : 
                  ($account->type == 'liability' ? 'warning' : 
                  ($account->type == 'equity' ? 'info' : 
                  ($account->type == 'revenue' ? 'success' : 'danger'))) 
                }}">
                  {{ ucfirst($account->type) }}
                </span>
              </td>
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
            <label class="form-label fw-semibold">Tipe Akun: <span class="text-danger">*</span></label>
            <select name="type" id="tipeAkun" class="form-select" required>
              <option value="">Pilih Tipe</option>
              <option value="asset">Asset (Aset)</option>
              <option value="liability">Liability (Kewajiban)</option>
              <option value="equity">Equity (Modal)</option>
              <option value="revenue">Revenue (Pendapatan)</option>
              <option value="expense">Expense (Beban)</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-semibold">Kategori: <span class="text-danger">*</span></label>
            <select name="account_category_id" id="kategoriAkun" class="form-select" required>
              <option value="">Pilih kategori</option>
            </select>
            <small class="text-muted">Pilih tipe terlebih dahulu</small>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-semibold">Kode Akun: <span class="text-danger">*</span></label>
            <input type="text" name="code" id="kodeAkun" class="form-control" placeholder="Contoh: 1100" required>
            <small class="text-muted">Masukkan kode akun unik</small>
          </div>
          
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Akun: <span class="text-danger">*</span></label>
            <input type="text" name="name" id="namaAkun" class="form-control" placeholder="Contoh: Kas" required>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Deskripsi:</label>
            <textarea name="description" id="deskripsi" class="form-control" rows="2" placeholder="Keterangan tambahan (opsional)"></textarea>
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


@endsection

@push('scripts')
<script src="{{ asset('js/akun.js') }}"></script>
@endpush