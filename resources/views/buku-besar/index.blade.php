 @extends('layouts.app')

@section('title', 'Daftar Hutang - SiBBesar') {{-- ini untuk judul di link yang diatas banget, bukan link --}}

@section('content')
 <div class="container mt-4">
    <h3 class="text-center fw-bold mb-4">Buku Besar</h3>

    <!-- Filter Periode -->
    <div class="card p-4 mb-4 shadow-sm">
      <div class="row g-3 align-items-end">
        <div class="col-md-4">
          <label for="dariTanggal" class="form-label">Dari Tanggal</label>
          <input type="date" class="form-control" id="dariTanggal">
        </div>
        <div class="col-md-4">
          <label for="sampaiTanggal" class="form-label">Sampai Tanggal</label>
          <input type="date" class="form-control" id="sampaiTanggal">
        </div>
        <div class="col-md-4 d-flex gap-2">
          <button class="btn btn-primary flex-grow-1" onclick="tampilkanBukuBesar()">Tampilkan Buku Besar</button>
          <button class="btn btn-success flex-grow-1" onclick="cetakPDF()">🖨 Cetak PDF</button>
        </div>
      </div>
    </div>

    <!-- Buku Besar -->
    <div class="card shadow-sm p-4" id="bukuBesar">
      <h5 class="text-center mb-3 fw-bold">BUKU BESAR</h5>
      <p class="text-center">Periode 2023-03-01 s/d 2023-03-31</p>

      <!-- Kas -->
      <h6 class="fw-bold bg-light p-2">[1100] Kas Ditangan</h6>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
@endsection