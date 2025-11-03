@extends('layouts.app')

@section('title', 'Daftar Hutang - SiBBesar')

@push('styles')
    <link href="{{ asset('css/hutang.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Main Content -->
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
                <h1 class="h2 mb-0">Daftar Hutang</h1>
                @if(isset($accounts) && $accounts->count() > 0)
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahHutangModal">
                        + Tambah Hutang Baru
                    </button>
                @else
                    <a href="{{ route('akun.create') }}" class="btn btn-warning">Buat Akun Terlebih Dahulu</a>
                @endif
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Table Controls -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <span>Show</span>
                                <select class="form-select mx-2 entries-select" aria-label="Show entries">
                                    <option value="10">10</option>
                                    <option value="25" selected>25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <span>entries</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="search-box">
                                <input type="text" class="form-control" placeholder="Search..." aria-label="Search">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Table -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Invoice No</th>
                                    <th>Vendor</th>
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
                                @forelse($hutang as $index => $item)
                                    <tr>
                                        <td>{{ $hutang->firstItem() + $index }}</td>
                                        <td>{{ $item->invoice_no }}</td>
                                        <td>{{ $item->vendor_name }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->invoice_date)->format('Y-m-d') }}</td>
                                        <td>{{ \Illuminate\Support\Carbon::parse($item->due_date)->format('Y-m-d') }}</td>
                                        <td class="currency">Rp {{ number_format($item->amount, 2, ',', '.') }}</td>
                                        <td class="currency">Rp {{ number_format($item->paid_amount, 2, ',', '.') }}</td>
                                        <td class="currency">Rp {{ number_format($item->remaining_amount, 2, ',', '.') }}</td>
                                        <td>{{ ucfirst($item->status) }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editHutangModal{{ $item->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('hutang.destroy', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak ada data hutang</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            @if($hutang->total() > 0)
                                Showing {{ $hutang->firstItem() }} to {{ $hutang->lastItem() }} of {{ $hutang->total() }} entries
                            @else
                                Showing 0 to 0 of 0 entries
                            @endif
                        </div>
                        {{ $hutang->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add New Hutang Modal -->
<div class="modal fade" id="tambahHutangModal" tabindex="-1" aria-labelledby="tambahHutangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahHutangModalLabel">Tambah Hutang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hutang.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="invoice_no" class="form-label required">Invoice No</label>
                        <input type="text" class="form-control @error('invoice_no') is-invalid @enderror" id="invoice_no" name="invoice_no" value="{{ old('invoice_no') }}" required>
                        @error('invoice_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="account_id" class="form-label required">Akun</label>
                        <select id="account_id" name="account_id" class="form-select @error('account_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Akun --</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" {{ old('account_id') == $acc->id ? 'selected' : '' }}>{{ $acc->code ?? '' }} - {{ $acc->name }}</option>
                            @endforeach
                        </select>
                        @error('account_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="vendor_name" class="form-label required">Vendor</label>
                        <input type="text" class="form-control @error('vendor_name') is-invalid @enderror" id="vendor_name" name="vendor_name" value="{{ old('vendor_name') }}" required>
                        @error('vendor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="invoice_date" class="form-label required">Invoice Date</label>
                            <input type="text" id="invoice_date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" placeholder="dd/mm/yyyy" value="{{ old('invoice_date') }}" required>
                            @error('invoice_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label required">Due Date</label>
                            <input type="text" id="due_date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" placeholder="dd/mm/yyyy" value="{{ old('due_date') }}" required>
                            @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label required">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="0.01" min="0" id="amount" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', 0) }}" required>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="paid_amount" class="form-label">Paid Amount (optional)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="0.01" min="0" id="paid_amount" name="paid_amount" class="form-control @error('paid_amount') is-invalid @enderror" value="{{ old('paid_amount', 0) }}">
                            @error('paid_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea id="notes" name="notes" class="form-control">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Hutang Modals -->
@foreach($hutang as $item)
<div class="modal fade" id="editHutangModal{{ $item->id }}" tabindex="-1" aria-labelledby="editHutangModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editHutangModalLabel{{ $item->id }}">Edit Hutang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('hutang.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="invoice_no{{ $item->id }}" class="form-label required">Invoice No</label>
                        <input type="text" class="form-control @error('invoice_no') is-invalid @enderror" id="invoice_no{{ $item->id }}" name="invoice_no" value="{{ old('invoice_no', $item->invoice_no) }}" required>
                        @error('invoice_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="account_id{{ $item->id }}" class="form-label required">Akun</label>
                        <select id="account_id{{ $item->id }}" name="account_id" class="form-select @error('account_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Akun --</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" {{ old('account_id', $item->account_id) == $acc->id ? 'selected' : '' }}>{{ $acc->code ?? '' }} - {{ $acc->name }}</option>
                            @endforeach
                        </select>
                        @error('account_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="vendor_name{{ $item->id }}" class="form-label required">Vendor</label>
                        <input type="text" class="form-control @error('vendor_name') is-invalid @enderror" id="vendor_name{{ $item->id }}" name="vendor_name" value="{{ old('vendor_name', $item->vendor_name) }}" required>
                        @error('vendor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="invoice_date{{ $item->id }}" class="form-label required">Invoice Date</label>
                            <input type="text" id="invoice_date{{ $item->id }}" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" placeholder="dd/mm/yyyy" value="{{ old('invoice_date', \Illuminate\Support\Carbon::parse($item->invoice_date)->format('d/m/Y')) }}" required>
                            @error('invoice_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="due_date{{ $item->id }}" class="form-label required">Due Date</label>
                            <input type="text" id="due_date{{ $item->id }}" name="due_date" class="form-control @error('due_date') is-invalid @enderror" placeholder="dd/mm/yyyy" value="{{ old('due_date', \Illuminate\Support\Carbon::parse($item->due_date)->format('d/m/Y')) }}" required>
                            @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="amount{{ $item->id }}" class="form-label required">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="0.01" min="0" id="amount{{ $item->id }}" name="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount', $item->amount) }}" required>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="paid_amount{{ $item->id }}" class="form-label">Paid Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="0.01" min="0" id="paid_amount{{ $item->id }}" name="paid_amount" class="form-control @error('paid_amount') is-invalid @enderror" value="{{ old('paid_amount', $item->paid_amount) }}">
                            @error('paid_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notes{{ $item->id }}" class="form-label">Notes</label>
                        <textarea id="notes{{ $item->id }}" name="notes" class="form-control">{{ old('notes', $item->notes) }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

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

        // Apply auto-format to date inputs
        const invoiceDateInput = document.getElementById('invoice_date');
        const dueeDateInput = document.getElementById('due_date');
        
        if (invoiceDateInput) {
            invoiceDateInput.addEventListener('input', function(e) {
                autoFormatDate(this);
            });
        }
        
        if (dueeDateInput) {
            dueeDateInput.addEventListener('input', function(e) {
                autoFormatDate(this);
            });
        }

        // Validate due_date is not before invoice_date on form submission
        const hutangForm = document.querySelector('form[action="{{ route('hutang.store') }}"]');
        if (hutangForm) {
            hutangForm.addEventListener('submit', function(e) {
                const invoiceDate = invoiceDateInput.value;
                const dueDate = dueeDateInput.value;
                
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
                                    dueeDateInput.focus();
                                }}
                            ]
                        });
                        return false;
                    }
                }
            });
        }

        // Apply same auto-format to edit modals
        document.querySelectorAll('[id^="invoice_date"]').forEach(function(input) {
            if (input.id !== 'invoice_date') { // Skip the main form
                input.addEventListener('input', function(e) {
                    autoFormatDate(this);
                });
            }
        });

        document.querySelectorAll('[id^="due_date"]').forEach(function(input) {
            if (input.id !== 'due_date') { // Skip the main form
                input.addEventListener('input', function(e) {
                    autoFormatDate(this);
                });
            }
        });

        // Focus the first input when the add modal opens
        var tambahModal = document.getElementById('tambahHutangModal');
        if (tambahModal) {
            tambahModal.addEventListener('shown.bs.modal', function () {
                var input = tambahModal.querySelector('#invoice_no');
                if (input) {
                    input.focus();
                }
            });
        }

        // If server-side validation failed for create, re-open the add modal so user can fix errors
        @if($errors->any() && old('invoice_no'))
            var bsModal = new bootstrap.Modal(document.getElementById('tambahHutangModal'));
            bsModal.show();
        @endif
    });
</script>
@endpush

@endsection
