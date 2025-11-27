@extends('layouts.app')

@section('content')
<div class="container-fluid mt-2">
    <!-- Header -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="mb-1 fw-bold text-primary">NERACA</h4>
                    <small class="text-muted">
                        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </small>
                </div>
                <div class="col-auto">
                    <a href="{{ route('neraca.export-pdf', request()->all()) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <a href="{{ route('neraca.export-excel', request()->all()) }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('neraca') }}" class="row g-2 align-items-end">
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

    <!-- Neraca Content -->
    <div class="row">
        @php
            $aktivaCategories = collect($neracaData)->filter(fn($item) => $item['category']->type === 'asset');
            $kewajibanCategories = collect($neracaData)->filter(fn($item) => $item['category']->type === 'liability');
            $ekuitasCategories = collect($neracaData)->filter(fn($item) => $item['category']->type === 'equity');
            
            // Gunakan abs() untuk memastikan semua nilai positif
            $totalAktiva = abs($aktivaCategories->sum('total'));
            $totalKewajiban = abs($kewajibanCategories->sum('total'));
            $totalEkuitas = abs($ekuitasCategories->sum('total'));
        @endphp

        <!-- LEFT SIDE: AKTIVA -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-primary text-white py-2">
                    <h5 class="mb-0">AKTIVA</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 120px;">Kode Akun</th>
                                <th>Nama Akun</th>
                                <th style="width: 150px;" class="text-end">Saldo</th>
                                <th style="width: 150px;" class="text-end">Jumlah Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($aktivaCategories as $categoryData)
                                <!-- Category Header -->
                                <tr class="table-secondary fw-bold">
                                    <td colspan="4">
                                        {{ $categoryData['category']->code }}-{{ $categoryData['category']->name }}
                                    </td>
                                </tr>
                                
                                <!-- Accounts under this category -->
                                @foreach($categoryData['accounts'] as $accountData)
                                    <tr>
                                        <td class="text-center">{{ $categoryData['category']->code }}-{{ $accountData['account']->code }}</td>
                                        <td>{{ $accountData['account']->name }}</td>
                                        <td class="text-end">
                                            {{ number_format(abs($accountData['balance']), 0, ',', '.') }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                
                                <!-- Category Subtotal -->
                                @if(count($categoryData['accounts']) > 0)
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-end">Jumlah {{ $categoryData['category']->name }}:</td>
                                        <td class="text-end">{{ number_format(abs($categoryData['total']), 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada data aktiva</td>
                                </tr>
                            @endforelse
                            
                            <!-- Total Aktiva -->
                            <tr class="table-primary fw-bold">
                                <td colspan="3" class="text-end">TOTAL AKTIVA:</td>
                                <td class="text-end">{{ number_format($totalAktiva, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: KEWAJIBAN & EKUITAS -->
        <div class="col-lg-6">
            <!-- KEWAJIBAN -->
            <div class="card mb-3">
                <div class="card-header bg-danger text-white py-2">
                    <h5 class="mb-0">KEWAJIBAN</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 120px;">Kode Akun</th>
                                <th>Nama Akun</th>
                                <th style="width: 150px;" class="text-end">Saldo</th>
                                <th style="width: 150px;" class="text-end">Jumlah Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kewajibanCategories as $categoryData)
                                <!-- Category Header -->
                                <tr class="table-secondary fw-bold">
                                    <td colspan="4">
                                        {{ $categoryData['category']->code }}-{{ $categoryData['category']->name }}
                                    </td>
                                </tr>
                                
                                <!-- Accounts under this category -->
                                @foreach($categoryData['accounts'] as $accountData)
                                    <tr>
                                        <td class="text-center">{{ $categoryData['category']->code }}-{{ $accountData['account']->code }}</td>
                                        <td>{{ $accountData['account']->name }}</td>
                                        <td class="text-end">
                                            {{ number_format(abs($accountData['balance']), 0, ',', '.') }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                
                                <!-- Category Subtotal -->
                                @if(count($categoryData['accounts']) > 0)
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-end">Jumlah {{ $categoryData['category']->name }}:</td>
                                        <td class="text-end">{{ number_format(abs($categoryData['total']), 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada data kewajiban</td>
                                </tr>
                            @endforelse
                            
                            <!-- Total Kewajiban -->
                            <tr class="table-danger fw-bold">
                                <td colspan="3" class="text-end">TOTAL KEWAJIBAN:</td>
                                <td class="text-end">{{ number_format($totalKewajiban, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- EKUITAS -->
            <div class="card">
                <div class="card-header bg-success text-white py-2">
                    <h5 class="mb-0">EKUITAS</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-bordered mb-0" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 120px;">Kode Akun</th>
                                <th>Nama Akun</th>
                                <th style="width: 150px;" class="text-end">Saldo</th>
                                <th style="width: 150px;" class="text-end">Jumlah Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ekuitasCategories as $categoryData)
                                <!-- Category Header -->
                                <tr class="table-secondary fw-bold">
                                    <td colspan="4">
                                        {{ $categoryData['category']->code }}-{{ $categoryData['category']->name }}
                                    </td>
                                </tr>
                                
                                <!-- Accounts under this category -->
                                @foreach($categoryData['accounts'] as $accountData)
                                    <tr>
                                        <td class="text-center">{{ $categoryData['category']->code }}-{{ $accountData['account']->code }}</td>
                                        <td>{{ $accountData['account']->name }}</td>
                                        <td class="text-end">
                                            {{ number_format(abs($accountData['balance']), 0, ',', '.') }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                
                                <!-- Category Subtotal -->
                                @if(count($categoryData['accounts']) > 0)
                                    <tr class="fw-bold">
                                        <td colspan="3" class="text-end">Jumlah {{ $categoryData['category']->name }}:</td>
                                        <td class="text-end">{{ number_format(abs($categoryData['total']), 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada data ekuitas</td>
                                </tr>
                            @endforelse
                            
                            <!-- Total Ekuitas -->
                            <tr class="table-success fw-bold">
                                <td colspan="3" class="text-end">TOTAL EKUITAS:</td>
                                <td class="text-end">{{ number_format($totalEkuitas, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total Kewajiban + Ekuitas -->
            <div class="card mt-3 border-primary">
                <div class="card-body py-2 bg-light">
                    <div class="row fw-bold text-primary">
                        <div class="col-8 text-end" style="font-size: 1.1rem;">
                            TOTAL KEWAJIBAN & EKUITAS:
                        </div>
                        <div class="col-4 text-end" style="font-size: 1.1rem;">
                            {{ number_format($totalKewajiban + $totalEkuitas, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
function exportToExcel() {
    const wb = XLSX.utils.book_new();
    const wsData = [
        ['NERACA'],
        ['Periode: {{ \Carbon\Carbon::parse($startDate)->format("d/m/Y") }} - {{ \Carbon\Carbon::parse($endDate)->format("d/m/Y") }}'],
        [],
        ['AKTIVA'],
        ['Kode Akun', 'Nama Akun', 'Saldo', 'Jumlah Saldo'],
    ];
    
    @foreach($aktivaCategories as $categoryData)
        wsData.push(['{{ $categoryData["category"]->code }}-{{ $categoryData["category"]->name }}', '', '', '']);
        @foreach($categoryData['accounts'] as $accountData)
            wsData.push([
                '{{ $categoryData["category"]->code }}-{{ $accountData["account"]->code }}',
                '{{ $accountData["account"]->name }}',
                {{ abs($accountData['balance']) }},
                ''
            ]);
        @endforeach
        wsData.push(['', '', 'Jumlah {{ $categoryData["category"]->name }}:', {{ abs($categoryData['total']) }}]);
    @endforeach
    
    wsData.push(['', '', 'TOTAL AKTIVA:', {{ abs($totalAktiva) }}]);
    wsData.push([]);
    
    const ws = XLSX.utils.aoa_to_sheet(wsData);
    XLSX.utils.book_append_sheet(wb, ws, 'Neraca');
    XLSX.writeFile(wb, 'Neraca_{{ \Carbon\Carbon::parse($startDate)->format("Y-m-d") }}.xlsx');
}

@media print {
    .btn, .card-header, form { display: none !important; }
    .card { border: none !important; box-shadow: none !important; }
    body { font-size: 10pt; }
}
</script>

@endsection
