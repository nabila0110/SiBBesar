@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="mb-0">
                        <i class="fas fa-file-invoice-dollar text-primary"></i> 
                        LAPORAN TRANSAKSI KEUANGAN
                    </h2>
                    <p class="text-muted mb-0">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
                </div>
                <div class="col-auto">
                    <!-- Filter Controls -->
                    <form method="GET" class="row g-2">
                        <div class="col-auto">
                            <label class="form-label">Tahun</label>
                            <select name="year" class="form-select form-select-sm" onchange="this.form.submit()">
                                @for ($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-auto">
                            <label class="form-label">&nbsp;</label><br>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="window.print()">
                                <i class="fas fa-print"></i> Cetak
                            </button>
                        </div>
                        <div class="col-auto">
                            <label class="form-label">&nbsp;</label><br>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="exportToExcel()">
                                <i class="fas fa-file-excel"></i> Export
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Header Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-md-3">
                            <small class="text-muted">Total Transaksi</small>
                            <h5 class="mb-0">{{ $transactions->count() }}</h5>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Total Debit</small>
                            <h5 class="mb-0 text-info">Rp {{ number_format($totalDebit, 2, ',', '.') }}</h5>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Total Kredit</small>
                            <h5 class="mb-0 text-warning">Rp {{ number_format($totalCredit, 2, ',', '.') }}</h5>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Saldo Net</small>
                            <h5 class="mb-0 text-success">Rp {{ number_format($totalDebit - $totalCredit, 2, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Transaction Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-table"></i> Daftar Transaksi Detail
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm" id="transactionTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 8%">No</th>
                            <th style="width: 10%">Tgl Transaksi</th>
                            <th style="width: 10%">No Jurnal</th>
                            <th style="width: 15%">Kode Akun</th>
                            <th style="width: 25%">Nama Akun</th>
                            <th style="width: 12%">Debit</th>
                            <th style="width: 12%">Kredit</th>
                            <th style="width: 20%">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $index => $transaction)
                            <tr class="transaction-row">
                                <td class="fw-bold text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ \Carbon\Carbon::parse($transaction->journal->transaction_date)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $transaction->journal->journal_no }}</span>
                                </td>
                                <td>
                                    <strong class="text-primary">{{ $transaction->account->code }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $transaction->account->name }}</strong>
                                    @if ($transaction->description)
                                        <br><small class="text-muted">{{ $transaction->description }}</small>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    @if ($transaction->debit > 0)
                                        <span class="text-primary fw-bold">
                                            Rp {{ number_format($transaction->debit, 2, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td style="text-align: right;">
                                    @if ($transaction->credit > 0)
                                        <span class="text-warning fw-bold">
                                            Rp {{ number_format($transaction->credit, 2, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $transaction->journal->description ?? '-' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox"></i> Tidak ada data transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="table-primary fw-bold" style="font-size: 1.1em;">
                            <td colspan="5">TOTAL</td>
                            <td style="text-align: right; border-top: 2px solid #0066cc;">
                                Rp {{ number_format($totalDebit, 2, ',', '.') }}
                            </td>
                            <td style="text-align: right; border-top: 2px solid #0066cc;">
                                Rp {{ number_format($totalCredit, 2, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            @if ($transactions instanceof \Illuminate\Pagination\Paginator)
                <div class="d-flex justify-content-center mt-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Summary by Account -->
    <div class="card mt-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <i class="fas fa-chart-pie"></i> Ringkasan Transaksi Per Akun
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                @forelse ($accountSummary as $summary)
                    <div class="col-md-6 mb-3">
                        <div class="card bg-light border-left-success" style="border-left: 4px solid #28a745;">
                            <div class="card-body py-2">
                                <div class="row">
                                    <div class="col-8">
                                        <h6 class="mb-1">{{ $summary['account_code'] }} - {{ $summary['account_name'] }}</h6>
                                        <small class="text-muted">{{ $summary['count'] }} transaksi</small>
                                    </div>
                                    <div class="col-4 text-right">
                                        <div style="font-size: 0.85em;">
                                            <strong class="text-info">Debit:</strong> Rp {{ number_format($summary['debit'], 0, ',', '.') }}<br>
                                            <strong class="text-warning">Kredit:</strong> Rp {{ number_format($summary['credit'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-4">
                        Tidak ada ringkasan akun
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Summary by Transaction Type -->
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <i class="fas fa-sitemap"></i> Ringkasan Berdasarkan Tipe Akun
            </h5>
        </div>
        <div class="card-body">
            <table class="table table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Tipe Akun</th>
                        <th style="width: 25%; text-align: right;">Total Debit</th>
                        <th style="width: 25%; text-align: right;">Total Kredit</th>
                        <th style="width: 25%; text-align: right;">Jumlah Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($typeSummaryRaw as $type => $data)
                        <tr>
                            <td>
                                <span class="badge bg-primary">{{ ucfirst($type) }}</span>
                            </td>
                            <td style="text-align: right;">
                                Rp {{ number_format($data->debit, 2, ',', '.') }}
                            </td>
                            <td style="text-align: right;">
                                Rp {{ number_format($data->credit, 2, ',', '.') }}
                            </td>
                            <td style="text-align: right;">
                                {{ $data->count }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn, .form-select, form, .alert {
            display: none !important;
        }
        .card {
            page-break-inside: avoid;
        }
        body {
            padding: 0 !important;
        }
    }

    .transaction-row {
        border-bottom: 1px solid #eee;
    }

    .transaction-row:hover {
        background-color: #f5f5f5;
    }

    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }

    .text-right {
        text-align: right;
    }

    table tfoot tr {
        background-color: #f9f9f9;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.min.js"></script>
<script>
    function exportToExcel() {
        const table = document.querySelector('#transactionTable');
        const ws = XLSX.utils.table_to_sheet(table);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Transaksi Keuangan");
        XLSX.writeFile(wb, `Laporan_Transaksi_{{ $year }}.xlsx`);
    }
</script>
@endsection
