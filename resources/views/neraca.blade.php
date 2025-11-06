@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="mb-0">
                        <i class="fas fa-balance-scale text-primary"></i> 
                        NERACA (BALANCE SHEET)
                    </h2>
                    <small class="text-muted">
                        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - 
                        {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                    </small>
                </div>
                <div class="col-auto">
                    <!-- Filter Controls -->
                    <form method="GET" class="row g-2">
                        <div class="col-auto">
                            <select name="year" class="form-select form-select-sm" onchange="this.form.submit()">
                                @for ($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                        Tahun {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="window.print()">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="exportToExcel()">
                                <i class="fas fa-file-excel"></i> Export
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Sheet Content -->
    <div class="row">
        <!-- ASSETS SECTION -->
        <div class="col-lg-6">
            <div class="card border-left-primary shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cube"></i> AKTIVA (ASSETS)
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20%">Kode</th>
                                <th style="width: 50%">Nama Akun</th>
                                <th style="width: 30%; text-align: right;">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($neracaData['assets'] as $asset)
                                <tr class="account-row">
                                    <td>
                                        <span class="badge bg-info">{{ $asset['code'] }}</span>
                                    </td>
                                    <td>
                                        {{ $asset['name'] }}
                                        @if ($asset['description'])
                                            <br><small class="text-muted">{{ $asset['description'] }}</small>
                                        @endif
                                    </td>
                                    <td style="text-align: right; font-weight: 500;">
                                        Rp {{ number_format($asset['balance'], 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        Tidak ada data aktiva
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Receivables Section -->
                            @if ($neracaData['receivables']->count() > 0)
                                <tr class="table-light">
                                    <td colspan="3" class="fw-bold">Piutang (Receivables)</td>
                                </tr>
                                @foreach ($neracaData['receivables'] as $receivable)
                                    <tr class="sub-account-row">
                                        <td>
                                            <span class="badge bg-warning">{{ $receivable->account->code }}</span>
                                        </td>
                                        <td>
                                            <small>{{ $receivable->invoice_no }} - {{ $receivable->customer_name }}</small>
                                        </td>
                                        <td style="text-align: right;">
                                            <small>Rp {{ number_format($receivable->remaining_amount, 2, ',', '.') }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="table-primary fw-bold">
                                <td colspan="2">TOTAL AKTIVA</td>
                                <td style="text-align: right; font-size: 1.1em;">
                                    Rp {{ number_format($neracaData['totalAssets'], 2, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- LIABILITIES & EQUITY SECTION -->
        <div class="col-lg-6">
            <!-- LIABILITIES -->
            <div class="card border-left-danger shadow mb-4">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-arrow-up"></i> PASSIVA - HUTANG (LIABILITIES)
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20%">Kode</th>
                                <th style="width: 50%">Nama Akun</th>
                                <th style="width: 30%; text-align: right;">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($neracaData['liabilities'] as $liability)
                                <tr class="account-row">
                                    <td>
                                        <span class="badge bg-danger">{{ $liability['code'] }}</span>
                                    </td>
                                    <td>
                                        {{ $liability['name'] }}
                                        @if ($liability['description'])
                                            <br><small class="text-muted">{{ $liability['description'] }}</small>
                                        @endif
                                    </td>
                                    <td style="text-align: right; font-weight: 500;">
                                        Rp {{ number_format($liability['balance'], 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        Tidak ada data hutang
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Payables Section -->
                            @if ($neracaData['payables']->count() > 0)
                                <tr class="table-light">
                                    <td colspan="3" class="fw-bold">Hutang (Payables)</td>
                                </tr>
                                @foreach ($neracaData['payables'] as $payable)
                                    <tr class="sub-account-row">
                                        <td>
                                            <span class="badge bg-warning">{{ $payable->account->code }}</span>
                                        </td>
                                        <td>
                                            <small>{{ $payable->invoice_no }} - {{ $payable->vendor_name }}</small>
                                        </td>
                                        <td style="text-align: right;">
                                            <small>Rp {{ number_format($payable->remaining_amount, 2, ',', '.') }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="table-danger fw-bold">
                                <td colspan="2">TOTAL HUTANG</td>
                                <td style="text-align: right; font-size: 1.1em;">
                                    Rp {{ number_format($neracaData['totalLiabilities'], 2, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- EQUITY -->
            <div class="card border-left-success shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-crown"></i> EKUITAS (EQUITY)
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20%">Kode</th>
                                <th style="width: 50%">Nama Akun</th>
                                <th style="width: 30%; text-align: right;">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($neracaData['equity'] as $eq)
                                <tr class="account-row">
                                    <td>
                                        <span class="badge bg-success">{{ $eq['code'] }}</span>
                                    </td>
                                    <td>
                                        {{ $eq['name'] }}
                                        @if ($eq['description'])
                                            <br><small class="text-muted">{{ $eq['description'] }}</small>
                                        @endif
                                    </td>
                                    <td style="text-align: right; font-weight: 500;">
                                        Rp {{ number_format($eq['balance'], 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        Tidak ada data ekuitas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-success fw-bold">
                                <td colspan="2">TOTAL EKUITAS</td>
                                <td style="text-align: right; font-size: 1.1em;">
                                    Rp {{ number_format($neracaData['totalEquity'], 2, ',', '.') }}
                                </td>
                            </tr>
                            <tr class="table-info fw-bold" style="font-size: 1.15em; background-color: #e7f3ff;">
                                <td colspan="2">TOTAL HUTANG + EKUITAS</td>
                                <td style="text-align: right;">
                                    Rp {{ number_format($neracaData['totalLiabilities'] + $neracaData['totalEquity'], 2, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Balance Verification -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-check-circle"></i> Verifikasi Neraca:</strong>
                <br>
                <div class="row mt-2">
                    <div class="col-md-4">
                        <strong>Total Aktiva:</strong><br>
                        <span style="font-size: 1.2em; color: #0066cc;">
                            Rp {{ number_format($neracaData['totalAssets'], 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <strong>Total Passiva:</strong><br>
                        <span style="font-size: 1.2em; color: #0066cc;">
                            Rp {{ number_format($neracaData['totalLiabilities'] + $neracaData['totalEquity'], 2, ',', '.') }}
                        </span>
                    </div>
                    <div class="col-md-4">
                        <strong>Selisih (Harus 0):</strong><br>
                        @php
                            $difference = $neracaData['totalAssets'] - ($neracaData['totalLiabilities'] + $neracaData['totalEquity']);
                            $differenceStatus = abs($difference) < 0.01 ? 'success' : 'danger';
                        @endphp
                        <span style="font-size: 1.2em; color: {{ $differenceStatus === 'success' ? '#28a745' : '#dc3545' }};">
                            Rp {{ number_format($difference, 2, ',', '.') }}
                            @if ($differenceStatus === 'success')
                                <i class="fas fa-check-circle"></i>
                            @else
                                <i class="fas fa-exclamation-circle"></i>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .alert-dismissible, form {
            display: none !important;
        }
        .card {
            page-break-inside: avoid;
        }
        body {
            padding: 0 !important;
            margin: 0 !important;
        }
    }

    .border-left-primary {
        border-left: 4px solid #0066cc !important;
    }

    .border-left-danger {
        border-left: 4px solid #dc3545 !important;
    }

    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }

    .account-row {
        background-color: #f9f9f9;
    }

    .account-row:hover {
        background-color: #f0f0f0;
    }

    .sub-account-row {
        background-color: #fafafa;
        border-left: 3px solid #ddd;
    }

    table tfoot tr {
        font-size: 1.05em;
    }
</style>

<script>
    function exportToExcel() {
        const table = document.querySelector('table');
        const ws = XLSX.utils.table_to_sheet(table);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Neraca");
        XLSX.writeFile(wb, `Neraca_{{ $year }}.xlsx`);
    }
</script>
@endsection
