@extends('layouts.app')

@section('content')
<div class="container-fluid mt-2">
    <!-- Header -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="mb-1 fw-bold text-primary">LAPORAN LABA RUGI</h4>
                    <small class="text-muted">
                        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </small>
                </div>
                <div class="col-auto">
                    <a href="{{ route('laba-rugi.export-pdf', request()->all()) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <a href="{{ route('laba-rugi.export-excel', request()->all()) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('laba-rugi') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small mb-1">Dari Tanggal</label>
                    <input type="date" name="dari_tanggal" class="form-control form-control-sm" 
                           value="{{ request('dari_tanggal', $startDate) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Sampai Tanggal</label>
                    <input type="date" name="sampai_tanggal" class="form-control form-control-sm" 
                           value="{{ request('sampai_tanggal', $endDate) }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Laba Rugi Content -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered mb-0" style="font-size: 0.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 120px;">Kode Akun</th>
                                <th>Nama Akun</th>
                                <th style="width: 150px;" class="text-end">Jumlah</th>
                                <th style="width: 150px;" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $revenueCategories = collect($labaRugiData)->filter(fn($item) => $item['category']->type === 'revenue');
                                $expenseCategories = collect($labaRugiData)->filter(fn($item) => $item['category']->type === 'expense');
                                
                                $totalRevenue = $revenueCategories->sum('total');
                                $totalExpense = $expenseCategories->sum('total');
                                $netIncome = $totalRevenue - $totalExpense;
                            @endphp

                            <!-- PENDAPATAN -->
                            @foreach($revenueCategories as $categoryData)
                                <!-- Category Header -->
                                <tr class="table-success">
                                    <td colspan="4" class="fw-bold">
                                        {{ $categoryData['category']->code }}-{{ $categoryData['category']->name }}
                                    </td>
                                </tr>
                                
                                <!-- Accounts -->
                                @foreach($categoryData['accounts'] as $accountData)
                                    <tr>
                                        <td>{{ $accountData['account']->category->code }}-{{ $accountData['account']->code }}</td>
                                        <td class="ps-4">{{ $accountData['account']->name }}</td>
                                        <td class="text-end">{{ number_format($accountData['balance'], 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                
                                <!-- Category Subtotal -->
                                <tr class="table-light fw-bold">
                                    <td colspan="3" class="text-end">TOTAL {{ strtoupper($categoryData['category']->name) }}:</td>
                                    <td class="text-end">{{ number_format($categoryData['total'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                            <!-- Total Pendapatan -->
                            <tr class="table-success fw-bold" style="font-size: 1rem;">
                                <td colspan="3" class="text-end">TOTAL PENDAPATAN:</td>
                                <td class="text-end">{{ number_format($totalRevenue, 0, ',', '.') }}</td>
                            </tr>

                            <tr><td colspan="4" style="height: 10px;"></td></tr>

                            <!-- BEBAN -->
                            @foreach($expenseCategories as $categoryData)
                                <!-- Category Header -->
                                <tr class="table-danger">
                                    <td colspan="4" class="fw-bold">
                                        {{ $categoryData['category']->code }}-{{ $categoryData['category']->name }}
                                    </td>
                                </tr>
                                
                                <!-- Accounts -->
                                @foreach($categoryData['accounts'] as $accountData)
                                    <tr>
                                        <td>{{ $accountData['account']->category->code }}-{{ $accountData['account']->code }}</td>
                                        <td class="ps-4">{{ $accountData['account']->name }}</td>
                                        <td class="text-end">{{ number_format($accountData['balance'], 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                
                                <!-- Category Subtotal -->
                                <tr class="table-light fw-bold">
                                    <td colspan="3" class="text-end">TOTAL {{ strtoupper($categoryData['category']->name) }}:</td>
                                    <td class="text-end">{{ number_format($categoryData['total'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                            <!-- Total Beban -->
                            <tr class="table-danger fw-bold" style="font-size: 1rem;">
                                <td colspan="3" class="text-end">TOTAL BEBAN:</td>
                                <td class="text-end">{{ number_format($totalExpense, 0, ',', '.') }}</td>
                            </tr>

                            <tr><td colspan="4" style="height: 10px;"></td></tr>

                            <!-- Laba/Rugi Bersih -->
                            <tr class="table-primary fw-bold" style="font-size: 1.1rem;">
                                <td colspan="3" class="text-end">
                                    @if($netIncome >= 0)
                                        LABA BERSIH:
                                    @else
                                        RUGI BERSIH:
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format(abs($netIncome), 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header, form, .navbar, .sidebar {
        display: none !important;
    }
    .container-fluid {
        margin-top: 0 !important;
    }
}
</style>

<script>
function exportToExcel() {
    alert('Fitur export Excel akan segera ditambahkan');
}
</script>
@endsection
