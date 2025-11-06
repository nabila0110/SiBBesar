<link rel="stylesheet" href="aset.css">
@extends('layouts.app')

@section('title', 'Dashboard - SiBBesar')

@section('content')

<!-- Container -->
  <div class="container mt-4 mb-5">
    <h3 class="fw-bold mb-4">Daftar Aset</h3>

    <div class="card shadow-sm p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAset">
          + Tambah Aset Baru
        </button>
      </div>

      <!-- Tabel Aset -->
      <div class="table-responsive">
        <table id="tabelAset" class="table table-bordered table-striped">
          <thead class="table-light">
            <tr class="text-center">
              <th>No</th>
              <th>Nama Aset</th>
              <th>Tanggal Perolehan</th>
              <th>Unit</th>
              <th>Umur Manfaat (Bulan)</th>
              <th>Harga Perolehan</th>
              <th>Akumulasi Penyusutan Perbulan</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Mobil Innova Venturer</td>
              <td>2023-03-02</td>
              <td>2</td>
              <td>36</td>
              <td>Rp 450.000.000</td>
              <td>Rp 25.000.000</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  

  <!-- Modal Tambah Aset Baru -->
  <div class="modal fade" id="modalTambahAset" tabindex="-1" aria-labelledby="modalTambahAsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-semibold" id="modalTambahAsetLabel">Tambah Aset Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formAset">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Aset</label>
                <input type="text" class="form-control" id="namaAset" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal Perolehan</label>
                <input type="text" class="form-control" id="tanggalPerolehan" placeholder="dd/mm/yyyy" required>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold">Unit</label>
                <input type="number" class="form-control" id="unit" required>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold">Umur Manfaat (Bulan)</label>
                <input type="number" class="form-control" id="umurManfaat" required>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold">Harga Perolehan</label>
                <input type="text" class="form-control" id="hargaPerolehan" placeholder="Rp" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Akumulasi Penyusutan Perbulan</label>
                <input type="text" class="form-control" id="akumulasiPenyusutan" placeholder="Rp" required>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="simpanAset">Simpan</button>
        </div>
      </div>
    </div>
  </div>

@endsection