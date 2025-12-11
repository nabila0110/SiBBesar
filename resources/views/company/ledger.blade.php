@extends('layouts.app')

@section('title', 'Buku Besar - ' . $company->name . ' - SiBBesar')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header dengan Info Company -->
    <div class="card mb-4 shadow-sm border-primary">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    @if($company->logo)
                        <img src="{{ asset('images/companies/' . $company->logo) }}" 
                             alt="{{ $company->name }}" 
                             class="img-fluid rounded"
                             style="max-height: 100px; object-fit: contain;">
                    @else
                        <i class="fas fa-building fa-5x text-muted"></i>
                    @endif
                </div>
                <div class="col-md-10">
                    <h2 class="mb-2">
                        <i class="fas fa-book"></i> Buku Besar
                    </h2>
                    <h4 class="text-primary mb-2">{{ $company->name }}</h4>
                    <p class="mb-1 text-muted"><strong>Kode:</strong> {{ $company->code }}</p>
                    @if($company->description)
                        <p class="mb-1 text-muted">{{ $company->description }}</p>
                    @endif
                    <div class="row mt-2">
                        @if($company->phone)
                            <div class="col-auto">
                                <small><i class="fas fa-phone text-primary"></i> {{ $company->phone }}</small>
                            </div>
                        @endif
                        @if($company->email)
                            <div class="col-auto">
                                <small><i class="fas fa-envelope text-primary"></i> {{ $company->email }}</small>
                            </div>
                        @endif
                        @if($company->address)
                            <div class="col-auto">
                                <small><i class="fas fa-map-marker-alt text-primary"></i> {{ $company->address }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" id="filterForm" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" class="form-control" name="dari_tanggal" 
                           value="{{ request('dari_tanggal') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" class="form-control" name="sampai_tanggal" 
                           value="{{ request('sampai_tanggal') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('company.ledger', $company->id) }}" class="btn btn-secondary w-100 mt-2">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('company.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Perusahaan
        </a>
        <div>
            <button onclick="exportPDF()" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
            <button onclick="exportExcel()" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- Data Buku Besar -->
    @if($groupedJournals->count() > 0)
        @foreach($groupedJournals as $accountId => $data)
            @php
                $firstJournal = $data['data']->first();
                $account = $firstJournal->account;
                $debit = $data['data']->sum('debit');
                $credit = $data['data']->sum('credit');
                $saldo = $debit - $credit;
            @endphp
            
            <div class="card mb-4 shadow-sm">
                <!-- Account Header -->
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0">
                                <i class="fas fa-folder"></i>
                                {{ $account->code }} - {{ $account->name }}
                            </h5>
                            <small>{{ $account->category->name }}</small>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="badge bg-light text-dark fs-6">
                                Saldo: 
                                @if($saldo >= 0)
                                    <span class="text-success">Rp {{ number_format($saldo, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-danger">Rp {{ number_format(abs($saldo), 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0" style="font-size: 0.7rem;">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 3%;">NO</th>
                                    <th style="width: 7%;">TANGGAL</th>
                                    <th style="width: 20%;">ITEM</th>
                                    <th style="width: 4%;">QTY</th>
                                    <th style="width: 6%;">SATUAN</th>
                                    <th style="width: 9%;" class="text-end">HARGA</th>
                                    <th style="width: 9%;" class="text-end">TOTAL</th>
                                    <th style="width: 8%;" class="text-end">PPN 11%</th>
                                    <th style="width: 8%;">PROJECT</th>
                                    <th style="width: 6%;">KET</th>
                                    <th style="width: 7%;">NOTA</th>
                                    <th style="width: 5%;">IN/OUT</th>
                                    <th style="width: 8%;">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $runningBalance = 0; @endphp
                                @foreach($data['data'] as $index => $journal)
                                    @php
                                        $runningBalance += ($journal->debit - $journal->credit);
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $data['from'] + $index }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($journal->transaction_date)->format('d/m/Y') }}</td>
                                        <td>{{ $journal->item }}</td>
                                        <td class="text-center">{{ number_format($journal->quantity, 0) }}</td>
                                        <td class="text-center">{{ $journal->satuan ?: '-' }}</td>
                                        <td class="text-end">Rp {{ number_format($journal->price, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($journal->total, 0, ',', '.') }}</td>
                                        <td class="text-end">
                                            @if($journal->tax)
                                                Rp {{ number_format($journal->ppn_amount, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $journal->project ?: '-' }}</td>
                                        <td class="text-center">{{ $journal->ket ?: '-' }}</td>
                                        <td>{{ $journal->nota }}</td>
                                        <td class="text-center">
                                            @if($journal->type === 'in')
                                                <span class="badge bg-success">IN</span>
                                            @else
                                                <span class="badge bg-danger">OUT</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($journal->payment_status === 'lunas')
                                                <span class="badge bg-success">LUNAS</span>
                                            @else
                                                <span class="badge bg-warning text-dark">TIDAK LUNAS</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-secondary fw-bold">
                                <tr>
                                    <td colspan="6" class="text-end">TOTAL {{ $account->name }}:</td>
                                    <td class="text-end">Rp {{ number_format($data['data']->sum('total'), 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($data['data']->sum('ppn_amount'), 0, ',', '.') }}</td>
                                    <td colspan="5"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Pagination per Account -->
                    @if($data['last_page'] > 1)
                        <div class="card-footer">
                            <nav>
                                <ul class="pagination pagination-sm mb-0 justify-content-center">
                                    @if($data['current_page'] > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="?page_{{ $accountId }}={{ $data['current_page'] - 1 }}{{ request('dari_tanggal') ? '&dari_tanggal='.request('dari_tanggal') : '' }}{{ request('sampai_tanggal') ? '&sampai_tanggal='.request('sampai_tanggal') : '' }}">
                                                Previous
                                            </a>
                                        </li>
                                    @endif

                                    @for($i = 1; $i <= $data['last_page']; $i++)
                                        <li class="page-item {{ $i == $data['current_page'] ? 'active' : '' }}">
                                            <a class="page-link" href="?page_{{ $accountId }}={{ $i }}{{ request('dari_tanggal') ? '&dari_tanggal='.request('dari_tanggal') : '' }}{{ request('sampai_tanggal') ? '&sampai_tanggal='.request('sampai_tanggal') : '' }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endfor

                                    @if($data['current_page'] < $data['last_page'])
                                        <li class="page-item">
                                            <a class="page-link" href="?page_{{ $accountId }}={{ $data['current_page'] + 1 }}{{ request('dari_tanggal') ? '&dari_tanggal='.request('dari_tanggal') : '' }}{{ request('sampai_tanggal') ? '&sampai_tanggal='.request('sampai_tanggal') : '' }}">
                                                Next
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                            <div class="text-center mt-2 small text-muted">
                                Menampilkan {{ $data['from'] }} - {{ $data['to'] }} dari {{ $data['total'] }} transaksi
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Tidak ada transaksi untuk perusahaan ini
            @if(request('dari_tanggal') || request('sampai_tanggal'))
                pada periode yang dipilih.
            @else
                .
            @endif
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function exportPDF() {
    const dari = document.querySelector('input[name="dari_tanggal"]').value;
    const sampai = document.querySelector('input[name="sampai_tanggal"]').value;
    
    let url = '/company/{{ $company->id }}/ledger/pdf?';
    if (dari) url += 'dari_tanggal=' + dari + '&';
    if (sampai) url += 'sampai_tanggal=' + sampai;
    
    window.open(url, '_blank');
}

function exportExcel() {
    const dari = document.querySelector('input[name="dari_tanggal"]').value;
    const sampai = document.querySelector('input[name="sampai_tanggal"]').value;
    
    let url = '/company/{{ $company->id }}/ledger/excel?';
    if (dari) url += 'dari_tanggal=' + dari + '&';
    if (sampai) url += 'sampai_tanggal=' + sampai;
    
    window.location.href = url;
}
</script>
@endsection
