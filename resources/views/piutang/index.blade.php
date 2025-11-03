@extends('layouts.app')

@section('title', 'Daftar Piutang - SiBBesar')

@push('styles')
    <link href="{{ asset('css/piutang.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                <h1 class="h2 mb-0">Daftar Piutang</h1>
                @if(isset($accounts) && $accounts->count() > 0)
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahPiutangModal">+ Tambah Piutang Baru</button>
                @else
                    <a href="{{ route('akun.create') }}" class="btn btn-warning">Buat Akun Terlebih Dahulu</a>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card mb-4 piutang-card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <span>Show</span>
                                <select class="form-select mx-2 entries-select" style="width:auto;">
                                    <option>10</option>
                                    <option selected>25</option>
                                </select>
                                <span>entries</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="Search...">
                        </div>
                    </div>

                    <div class="table-responsive piutang-table">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Remaining</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($piutang as $i => $item)
                                    <tr>
                                        <td>{{ $piutang->firstItem() + $i }}</td>
                                        <td>{{ $item->invoice_no }}</td>
                                        <td>{{ $item->customer_name }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->invoice_date)->format('Y-m-d') }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->due_date)->format('Y-m-d') }}</td>
                                        <td class="currency">Rp {{ number_format($item->amount, 2, ',', '.') }}</td>
                                        <td class="currency">Rp {{ number_format($item->paid_amount, 2, ',', '.') }}</td>
                                        <td class="currency">Rp {{ number_format($item->remaining_amount, 2, ',', '.') }}</td>
                                        <td>{{ ucfirst($item->status) }}</td>
                                        <td class="piutang-actions">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editPiutangModal{{ $item->id }}">Edit</button>
                                                <form action="{{ route('piutang.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="text-center">Tidak ada data piutang</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            @if($piutang->total() > 0)
                                Showing {{ $piutang->firstItem() }} to {{ $piutang->lastItem() }} of {{ $piutang->total() }} entries
                            @endif
                        </div>
                        {{ $piutang->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="tambahPiutangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Piutang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('piutang.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Invoice No</label>
                        <input type="text" name="invoice_no" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Akun</label>
                        <select name="account_id" class="form-select" required>
                            <option value="">-- Pilih Akun --</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->code ?? '' }} - {{ $acc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Invoice Date</label><input type="text" name="invoice_date" class="form-control" placeholder="dd/mm/yyyy" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Due Date</label><input type="text" name="due_date" class="form-control" placeholder="dd/mm/yyyy" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Amount</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Paid Amount</label><input type="number" step="0.01" name="paid_amount" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Notes</label><textarea name="notes" class="form-control"></textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modals -->
@foreach($piutang as $item)
<div class="modal fade" id="editPiutangModal{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Piutang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('piutang.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Invoice No</label><input type="text" name="invoice_no" value="{{ $item->invoice_no }}" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Customer</label><input type="text" name="customer_name" value="{{ $item->customer_name }}" class="form-control" required></div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Invoice Date</label><input type="text" name="invoice_date" value="{{ \Illuminate\Support\Carbon::parse($item->invoice_date)->format('d/m/Y') }}" class="form-control" placeholder="dd/mm/yyyy" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Due Date</label><input type="text" name="due_date" value="{{ \Illuminate\Support\Carbon::parse($item->due_date)->format('d/m/Y') }}" class="form-control" placeholder="dd/mm/yyyy" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Amount</label><input type="number" step="0.01" name="amount" value="{{ $item->amount }}" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Paid Amount</label><input type="number" step="0.01" name="paid_amount" value="{{ $item->paid_amount }}" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Notes</label><textarea name="notes" class="form-control">{{ $item->notes }}</textarea></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function(){
        // Auto-format date input: dd/mm/yyyy
        function autoFormatDate(input) {
            let value = input.value.replace(/\D/g, ''); // Remove non-digits
            if (value.length >= 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            if (value.length >= 5) {
                value = value.substring(0, 5) + '/' + value.substring(5, 9);
            }
            input.value = value;
        }

        // Apply auto-format to all date inputs
        document.querySelectorAll('input[name="invoice_date"]').forEach(function(input) {
            input.addEventListener('input', function(e) {
                autoFormatDate(this);
            });
        });

        document.querySelectorAll('input[name="due_date"]').forEach(function(input) {
            input.addEventListener('input', function(e) {
                autoFormatDate(this);
            });
        });

        // Validate due_date is not before invoice_date on form submission
        document.querySelectorAll('form[action*="piutang"]').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const invoiceDateInput = form.querySelector('input[name="invoice_date"]');
                const dueDateInput = form.querySelector('input[name="due_date"]');
                
                if (invoiceDateInput && dueDateInput) {
                    const invoiceDate = invoiceDateInput.value;
                    const dueDate = dueDateInput.value;
                    
                    // Parse dates in dd/mm/yyyy format
                    const invParts = invoiceDate.split('/');
                    const dueParts = dueDate.split('/');
                    
                    if (invParts.length === 3 && dueParts.length === 3) {
                        const invDate = new Date(invParts[2], invParts[1] - 1, invParts[0]);
                        const dueDate_obj = new Date(dueParts[2], dueParts[1] - 1, dueParts[0]);
                        
                        if (dueDate_obj < invDate) {
                            e.preventDefault();
                            showCustomAlert({
                                title: 'Validasi Tanggal',
                                message: 'Tanggal jatuh tempo tidak boleh lebih awal dari tanggal invoice',
                                type: 'error',
                                buttons: [
                                    { text: 'OK', type: 'primary', callback: function() {
                                        closeCustomAlert();
                                        dueDateInput.focus();
                                    }}
                                ]
                            });
                            return false;
                        }
                    }
                }
            });
        });

        var modal = document.getElementById('tambahPiutangModal');
        if(modal){
            modal.addEventListener('shown.bs.modal', function(){
                var el = modal.querySelector('input[name="invoice_no"]'); if(el) el.focus();
            });
        }
        @if($errors->any())
            var m = new bootstrap.Modal(document.getElementById('tambahPiutangModal'));
            m.show();
        @endif
    });
</script>
@endpush

@endsection

