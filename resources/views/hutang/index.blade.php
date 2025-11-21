@extends('layouts.app')

@section('title', 'Daftar Hutang - SiBBesar')

@section('content')
<style>
    .table-hutang {
        font-size: 0.75rem;
    }

    .table-hutang th,
    .table-hutang td {
        padding: 0.4rem 0.2rem;
        font-size: 0.75rem;
    }

    .table-responsive {
        overflow-x: auto;
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
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                <h1 class="h2 mb-0">Daftar Hutang (Belum Lunas)</h1>
                <a href="{{ route('jurnal.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data Jurnal
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Main Table -->
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0 table-hutang">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th style="width: 100px;">Tanggal</th>
                                    <th style="width: 180px;">Item</th>
                                    <th style="width: 70px;" class="text-end">Qty</th>
                                    <th style="width: 80px;">Satuan</th>
                                    <th style="width: 100px;" class="text-end">@</th>
                                    <th style="width: 120px;" class="text-end">Total</th>
                                    <th style="width: 100px;" class="text-end">PPN 11%</th>
                                    <th style="width: 130px;" class="text-end">Final Total</th>
                                    <th style="width: 120px;">Project</th>
                                    <th style="width: 150px;">Perusahaan</th>
                                    <th style="width: 100px;">Ket</th>
                                    <th style="width: 100px;">Nota</th>
                                    <th style="width: 90px;" class="text-center">Status</th>
                                    <th style="width: 200px;">Account</th>
                                    <th style="width: 90px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $subtotal = 0;
                                    $subtotalPPN = 0;
                                    $grandTotal = 0;
                                @endphp
                                @forelse($journals as $index => $journal)
                                    @php
                                        $subtotal += $journal->total;
                                        $subtotalPPN += $journal->ppn_amount;
                                        $grandTotal += $journal->final_total;
                                    @endphp
                                    <tr>
                                        <td class="text-center">{{ $journals->firstItem() + $index }}</td>
                                        <td>{{ \Carbon\Carbon::parse($journal->transaction_date)->format('d/m/Y') }}</td>
                                        <td>{{ $journal->item }}</td>
                                        <td class="text-end">{{ number_format($journal->quantity, 0, ',', '.') }}</td>
                                        <td>{{ $journal->satuan }}</td>
                                        <td class="text-end">{{ number_format($journal->price, 0, ',', '.') }}</td>
                                        <td class="text-end">{{ number_format($journal->total, 0, ',', '.') }}</td>
                                        <td class="text-end">{{ $journal->tax ? number_format($journal->ppn_amount, 0, ',', '.') : '-' }}</td>
                                        <td class="text-end fw-bold">{{ number_format($journal->final_total, 0, ',', '.') }}</td>
                                        <td>{{ $journal->project ?? '-' }}</td>
                                        <td>{{ $journal->company ?? '-' }}</td>
                                        <td>{{ $journal->ket ?? '-' }}</td>
                                        <td>{{ $journal->nota ?? '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">HUTANG</span>
                                        </td>
                                        <td><small>{{ $journal->account ? $journal->account->code . ' - ' . $journal->account->name : '-' }}</small></td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('jurnal.edit', $journal->id) }}" class="btn btn-sm btn-info" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                        data-id="{{ $journal->id }}" 
                                                        data-item="{{ $journal->item }}"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <form id="delete-form-{{ $journal->id }}" action="{{ route('jurnal.destroy', $journal->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="16" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                            <p class="text-muted mb-0">Tidak ada data hutang yang belum lunas</p>
                                        </td>
                                    </tr>
                                @endforelse
                                @if($journals->count() > 0)
                                    <tr class="table-secondary fw-bold">
                                        <td colspan="6" class="text-end">TOTAL:</td>
                                        <td class="text-end">{{ number_format($subtotal, 0, ',', '.') }}</td>
                                        <td class="text-end">{{ number_format($subtotalPPN, 0, ',', '.') }}</td>
                                        <td class="text-end">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                                        <td colspan="7"></td>
                                    </tr>
                                @endif
                            </tbody>
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
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SweetAlert2 for delete confirmation
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const journalId = this.getAttribute('data-id');
                const journalItem = this.getAttribute('data-item');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    html: `Anda akan menghapus data:<br><strong>${journalItem}</strong>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${journalId}`).submit();
                    }
                });
            });
        });
    });
</script>
@endpush

@endsection
