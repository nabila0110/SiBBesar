@extends('layouts.app')

@section('title', 'Tambah Hutang - SiBBesar')

@push('styles')
<link href="{{ asset('css/hutang.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="mb-0">Tambah Hutang Baru</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('hutang.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="invoice_no" class="form-label required">Invoice No</label>
                            <input type="text" id="invoice_no" name="invoice_no" class="form-control @error('invoice_no') is-invalid @enderror" value="{{ old('invoice_no') }}" required>
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
                            <input type="text" id="vendor_name" name="vendor_name" class="form-control @error('vendor_name') is-invalid @enderror" value="{{ old('vendor_name') }}" required>
                            @error('vendor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="invoice_date" class="form-label required">Invoice Date</label>
                                <input type="date" id="invoice_date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date') }}" required>
                                @error('invoice_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label required">Due Date</label>
                                <input type="date" id="due_date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}" required>
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

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('hutang.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('invoice_no');
        if(el) el.focus();
    });
</script>
@endpush

@endsection