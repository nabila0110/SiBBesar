@extends('layouts.app')

@section('title', 'Jurnal Umum - SiBBesar')

@section('content')

<style>
    .table-currency {
        text-align: right;
    }

    .table-small {
        font-size: 0.85rem;
    }

    .badge-in {
        background-color: #28a745;
        color: white;
    }

    .badge-out {
        background-color: #dc3545;
        color: white;
    }

    .badge-hutang {
        background-color: #ffc107;
        color: #000;
    }

    .badge-piutang {
        background-color: #17a2b8;
        color: white;
    }
    
    /* Perbaikan untuk table yang lebar */
    .container-fluid {
        max-width: 100%;
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 1rem;
    }
    
    #tabelJurnal {
        width: 100%;
        font-size: 0.7rem;
    }
    
    #tabelJurnal th,
    #tabelJurnal td {
        padding: 0.3rem 0.2rem;
        font-size: 0.7rem;
    }
    
    /* Kolom Item bisa wrap */
    #tabelJurnal th:nth-child(3),
    #tabelJurnal td:nth-child(3) {
        white-space: normal;
        max-width: 200px;
    }

    /* Pagination styling */
    .pagination {
        margin: 0;
        gap: 0.25rem;
    }
    
    .pagination .page-item {
        margin: 0 2px;
    }
    
    .pagination .page-link {
        color: #6c757d;
        background-color: #fff;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: all 0.15s ease-in-out;
    }
    
    .pagination .page-link:hover {
        color: #0056b3;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
        z-index: 3;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .pagination .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: none;
    }
</style>

<div class="container-fluid mt-0">
    <h2 class="mb-4">Jurnal Umum</h2>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- TOMBOL AKSI -->
    <div class="mb-4 d-flex flex-wrap gap-2">
        <a href="{{ route('jurnal.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Jurnal
        </a>
        <a href="{{ route('jurnal.export.excel', request()->all()) }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <a href="{{ route('jurnal.export.pdf', request()->all()) }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
    </div>

    <!-- FILTER TANGGAL -->
    <form method="GET" action="{{ route('jurnal.index') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" class="form-control" name="dari_tanggal" value="{{ request('dari_tanggal') }}" />
        </div>
        <div class="col-md-3">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" class="form-control" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}" />
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-info">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </a>
        </div>
    </form>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 text-center">DAFTAR JURNAL</h5>
        </div>
        <div class="card-body p-0">
            @if ($journals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-small mb-0" id="tabelJurnal">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Tanggal</th>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Satuan</th>
                                <th class="table-currency">Harga</th>
                                <th class="table-currency">Total</th>
                                <th class="table-currency">PPN 11%</th>
                                <th>Project</th>
                                <th class="text-center">Ket</th>
                                <th>Nota</th>
                                <th class="text-center">IN/OUT</th>
                                <th class="text-center">Status</th>
                                <th>Account</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = ($journals->currentPage() - 1) * $journals->perPage() + 1; @endphp
                            @foreach ($journals as $journal)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($journal->transaction_date)->format('d/m/Y') }}</td>
                                    <td>{{ $journal->item }}</td>
                                    <td class="text-center">{{ number_format($journal->quantity, 0) }}</td>
                                    <td class="text-center">{{ $journal->satuan ?: '-' }}</td>
                                    <td class="table-currency">Rp {{ number_format($journal->price, 0, ',', '.') }}</td>
                                    <td class="table-currency">Rp {{ number_format($journal->total, 0, ',', '.') }}</td>
                                    <td class="table-currency">
                                        @if ($journal->tax)
                                            Rp {{ number_format($journal->ppn_amount, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $journal->project ?: '-' }}</td>
                                    <td class="text-center">{{ $journal->ket ?: '-' }}</td>
                                    <td>{{ $journal->nota }}</td>
                                    <td class="text-center">
                                        @if ($journal->type === 'in')
                                            <span class="badge badge-in">IN</span>
                                        @else
                                            <span class="badge badge-out">OUT</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($journal->payment_status === 'lunas')
                                            <span class="badge bg-success">LUNAS</span>
                                        @else
                                            <span class="badge bg-warning text-dark">TIDAK LUNAS</span>
                                        @endif
                                        @if ($journal->is_hutang)
                                            <br><small class="badge badge-hutang mt-1">HUTANG</small>
                                        @elseif($journal->is_piutang)
                                            <br><small class="badge badge-piutang mt-1">PIUTANG</small>
                                        @endif
                                    </td>
                                    <td><small>{{ $journal->account?->code }} - {{ $journal->account?->name }}</small></td>
                                    <td class="text-center">
                                        <a href="{{ route('jurnal.edit', $journal->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                data-id="{{ $journal->id }}" 
                                                data-item="{{ $journal->item }}"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $journal->id }}" 
                                              action="{{ route('jurnal.destroy', $journal->id) }}" 
                                              method="POST" 
                                              style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="6" class="text-end fw-bold">TOTAL:</td>
                                <td class="table-currency fw-bold">Rp {{ number_format($journals->sum('total'), 0, ',', '.') }}</td>
                                <td class="table-currency fw-bold">Rp {{ number_format($journals->sum('ppn_amount'), 0, ',', '.') }}</td>
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">GRAND TOTAL (Termasuk PPN):</td>
                                <td class="table-currency fw-bold" colspan="2">Rp {{ number_format($journals->sum('final_total'), 0, ',', '.') }}</td>
                                <td colspan="8"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="text-muted small">
                        Showing {{ $journals->firstItem() ?? 0 }} to {{ $journals->lastItem() ?? 0 }} of {{ $journals->total() }} results
                    </div>
                    <div>
                        {{ $journals->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @else
                <div class="alert alert-info m-3">
                    <i class="fas fa-info-circle"></i> Belum ada data jurnal. <a href="{{ route('jurnal.create') }}">Tambah jurnal baru</a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Handle delete button
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.delete-btn');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const item = this.getAttribute('data-item');
            
            if (confirm(`Apakah Anda yakin ingin menghapus jurnal "${item}"?`)) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    });
});
</script>

@endsection
