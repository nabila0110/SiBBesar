@extends('layouts.app')

@section('title', 'Jenis Barang - SiBBesar')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Laba Rugi</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #eef3f8;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    h3, h5 {
      font-weight: 600;
    }
    .btn-custom {
      padding: 8px 20px;
      font-size: 14px;
      border-radius: 6px;
    }
    @media print {
      .no-print {
        display: none !important;
      }
    }
  </style>
</head>
<body>

  <div class="container my-5">
    <div class="card p-4">

      <!-- Bagian Header -->
      <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h3>Laporan Laba Rugi</h3>
        <div class="d-flex">
          <input type="number" id="tahun" name="year" class="form-control w-auto me-2" value="{{ $year ?? date('Y') }}">
          <button class="btn btn-danger btn-custom me-2" onclick="buatStatement()">Buat Statement</button>
          <button class="btn btn-primary btn-custom" onclick="window.print()">Cetak</button>
        </div>
      </div>

      <!-- Judul -->
  <h5 class="text-center mb-0">Laporan Laba Rugi</h5>
  <p class="text-center text-muted" id="periode">Periode: {{ $year ?? date('Y') }}-01-01 s/d {{ $year ?? date('Y') }}-12-31</p>

      <!-- Tabel Pendapatan -->
      <table class="table">
        <thead class="table-light">
          <tr><th colspan="2">Pendapatan</th></tr>
        </thead>
        <tbody>
          @php
            function _rupiah($n) { if ($n === null) $n = 0; return 'Rp ' . number_format($n, 0, ',', '.'); }
          @endphp

          @foreach(($revenues['groups'] ?? []) as $group)
            <tr class="table-secondary"><td colspan="2">{{ $group['category']->name }}</td></tr>
            @forelse($group['items'] as $item)
              <tr>
                <td>[{{ $item['code'] }}] {{ $item['name'] }}</td>
                <td class="text-end">{{ _rupiah($item['balance']) }}</td>
              </tr>
            @empty
              <tr><td colspan="2" class="text-center text-muted">Tidak ada akun</td></tr>
            @endforelse
            <tr class="fw-bold"><td>Total {{ $group['category']->name }}</td><td class="text-end">{{ _rupiah($group['total']) }}</td></tr>
          @endforeach

          <tr class="fw-bold table-light"><td>Total Pendapatan Bersih</td><td class="text-end">{{ _rupiah($revenues['total'] ?? 0) }}</td></tr>
        </tbody>

        <!-- Tabel Pengeluaran -->
        <thead class="table-light mt-3">
          <tr><th colspan="2">Pengeluaran</th></tr>
        </thead>
        <tbody>
          @foreach(($expenses['groups'] ?? []) as $group)
            <tr class="table-secondary"><td colspan="2">{{ $group['category']->name }}</td></tr>
            @forelse($group['items'] as $item)
              <tr>
                <td>[{{ $item['code'] }}] {{ $item['name'] }}</td>
                <td class="text-end">{{ _rupiah($item['balance']) }}</td>
              </tr>
            @empty
              <tr><td colspan="2" class="text-center text-muted">Tidak ada akun</td></tr>
            @endforelse
            <tr class="fw-bold"><td>Total {{ $group['category']->name }}</td><td class="text-end">{{ _rupiah($group['total']) }}</td></tr>
          @endforeach

          <tr class="fw-bold"><td>Total Beban</td><td class="text-end">{{ _rupiah($expenses['total'] ?? 0) }}</td></tr>
        </tbody>

        <!-- Hasil Akhir -->
        <tfoot>
          <tr class="fw-bold table-light">
            <td>Total Laba/Rugi</td>
            <td class="text-end">{{ _rupiah( ($revenues['total'] ?? 0) - ($expenses['total'] ?? 0) ) }}</td>
          </tr>
        </tfoot>
      </table>

   
    </div>
  </div>

  <script>
    function buatStatement() {
      const tahun = document.getElementById("tahun").value;
      document.getElementById("periode").textContent =
        "Periode: " + tahun + "-1-1 s/d " + tahun + "-12-31";
      alert("Statement tahun " + tahun + " berhasil dibuat!");
    }
  </script>

</body>
</html>
@endsection