@extends('layouts.app')

@section('title', 'Jenis Barang - SiBBesar')

@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laporan Posisi Keuangan</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #eef3f8;
      font-family: 'Segoe UI', sans-serif;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      background-color: #fff;
    }

    h3, h5 {
      font-weight: 600;
      color: #333;
    }

    .btn-custom {
      border-radius: 6px;
      padding: 8px 18px;
      font-size: 14px;
    }

    .table th {
      background-color: #f8f9fa;
      text-transform: uppercase;
      font-size: 13px;
    }

    .text-muted {
      font-size: 14px;
    }

    @media print {
      .no-print {
        display: none !important;
      }
      body {
        background-color: #fff;
      }
      .card {
        box-shadow: none;
        border: none;
      }
    }
  </style>
</head>
<body>

  <div class="container my-5">
    <div class="card p-4">
      <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h3 class="mb-0">Laporan Posisi Keuangan</h3>
        <div>
          <input type="number" id="tahun" name="year" class="form-control d-inline w-auto me-2" value="{{ $year ?? date('Y') }}">
          <button class="btn btn-danger btn-custom me-2" onclick="buatStatement()">Buat Statement</button>
          <button class="btn btn-primary btn-custom" onclick="window.print()">Cetak</button>
        </div>
      </div>

      <h5 class="text-center mb-0">Laporan Posisi Keuangan</h5>
      <p class="text-center text-muted mb-4" id="periode">Periode Berakhir: 31 Desember {{ $year ?? date('Y') }}</p>

      <!-- Dynamic sections (Aktiva / Kewajiban / Modal) populated from DB -->
      @php
        /**
         * Simple Indonesian currency formatter for display (no decimals)
         */
        function _rupiah($n) {
            if ($n === null) $n = 0;
            return 'Rp ' . number_format($n, 0, ',', '.');
        }
      @endphp

      <table class="table table-bordered">
        {{-- Assets --}}
        @foreach(($assets['groups'] ?? []) as $group)
          <thead>
            <tr class="table-light">
              <th colspan="2">{{ strtoupper($group['category']->name) }} @if($group['category']->name) / {{ strtoupper($group['category']->type) }}@endif</th>
            </tr>
          </thead>
          <tbody>
            @forelse($group['items'] as $item)
              <tr>
                <td>[{{ $item['code'] }}] {{ $item['name'] }}</td>
                <td class="text-end">{{ _rupiah($item['balance']) }}</td>
              </tr>
            @empty
              <tr><td colspan="2" class="text-center text-muted">Tidak ada akun</td></tr>
            @endforelse
            <tr class="fw-bold"><td>Total {{ $group['category']->name }}</td><td class="text-end">{{ _rupiah($group['total']) }}</td></tr>
          </tbody>
        @endforeach

        {{-- Total Aset --}}
        <tbody>
          <tr class="fw-bold table-light"><td>Total Aset / Aktiva</td><td class="text-end">{{ _rupiah($assets['total'] ?? 0) }}</td></tr>
        </tbody>

        {{-- Liabilities --}}
        @foreach(($liabilities['groups'] ?? []) as $group)
          <thead>
            <tr class="table-light">
              <th colspan="2">{{ strtoupper($group['category']->name) }} / {{ strtoupper($group['category']->type) }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($group['items'] as $item)
              <tr>
                <td>[{{ $item['code'] }}] {{ $item['name'] }}</td>
                <td class="text-end">{{ _rupiah($item['balance']) }}</td>
              </tr>
            @empty
              <tr><td colspan="2" class="text-center text-muted">Tidak ada akun</td></tr>
            @endforelse
            <tr class="fw-bold"><td>Total {{ $group['category']->name }}</td><td class="text-end">{{ _rupiah($group['total']) }}</td></tr>
          </tbody>
        @endforeach

        {{-- Total Kewajiban --}}
        <tbody>
          <tr class="fw-bold"><td>Total Kewajiban</td><td class="text-end">{{ _rupiah($liabilities['total'] ?? 0) }}</td></tr>
        </tbody>

        {{-- Equity / Modal --}}
        @foreach(($equities['groups'] ?? []) as $group)
          <thead>
            <tr class="table-light">
              <th colspan="2">{{ strtoupper($group['category']->name) }} / {{ strtoupper($group['category']->type) }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($group['items'] as $item)
              <tr>
                <td>[{{ $item['code'] }}] {{ $item['name'] }}</td>
                <td class="text-end">{{ _rupiah($item['balance']) }}</td>
              </tr>
            @empty
              <tr><td colspan="2" class="text-center text-muted">Tidak ada akun</td></tr>
            @endforelse
            <tr class="fw-bold"><td>Total {{ $group['category']->name }}</td><td class="text-end">{{ _rupiah($group['total']) }}</td></tr>
          </tbody>
        @endforeach

        {{-- Total Kewajiban & Modal --}}
        <tbody>
          <tr class="fw-bold table-light"><td>Total Kewajiban & Modal</td><td class="text-end">{{ _rupiah( ($liabilities['total'] ?? 0) + ($equities['total'] ?? 0) ) }}</td></tr>
        </tbody>
      </table>

      
    </div>
  </div>

  <script>
    function buatStatement() {
      const tahun = document.getElementById("tahun").value;
      // Reload the page with the selected year as a query parameter so the controller computes date-range balances
      const params = new URLSearchParams(window.location.search);
      params.set('year', tahun);
      window.location.search = params.toString();
    }
  </script>
</body>
</html>
@endsection