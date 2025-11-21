@extends('layouts.app')

@section('title', 'Edit Jurnal - SiBBesar')

@section('content')
<div class="container-fluid mt-4">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book"></i> Edit Jurnal</h2>
        <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Error!</strong> Periksa kembali input Anda:
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('jurnal.update', $journal->id) }}" id="journalForm">
        @csrf
        @method('PUT')
        
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informasi Transaksi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="transaction_date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('transaction_date') is-invalid @enderror" 
                                   id="transaction_date" name="transaction_date" value="{{ old('transaction_date', $journal->transaction_date) }}" required>
                            @error('transaction_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="item" class="form-label">Item / Keterangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('item') is-invalid @enderror" 
                                   id="item" name="item" placeholder="Contoh: Semen, Bata, dll" value="{{ old('item', $journal->item) }}" required>
                            @error('item')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity (Qty) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity', $journal->quantity) }}" min="0" required>
                            @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control @error('satuan') is-invalid @enderror" 
                                   id="satuan" name="satuan" placeholder="sak, unit, pcs" value="{{ old('satuan', $journal->satuan) }}" list="satuan-list">
                            <datalist id="satuan-list">
                                <option value="sak">
                                <option value="unit">
                                <option value="pcs">
                                <option value="M">
                                <option value="lbr">
                            </datalist>
                            @error('satuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="price_display" class="form-label">Harga Satuan (@) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control @error('price') is-invalid @enderror" 
                                       id="price_display" placeholder="0" required>
                                <input type="hidden" id="price" name="price" value="{{ old('price', $journal->price) }}">
                            </div>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="total_display" class="form-label">Total (Sebelum PPN)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control bg-light" id="total_display" readonly>
                            </div>
                            <small class="text-muted">Otomatis: Qty × Harga</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" id="tax" name="tax" value="1" {{ old('tax', $journal->tax) ? 'checked' : '' }}>
                            <label class="form-check-label" for="tax">
                                <strong>PPN 11%</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="ppn_display" class="form-label">PPN 11%</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control bg-light" id="ppn_display" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="mb-3">
                            <label for="final_total_display" class="form-label"><strong>Total Akhir (Setelah PPN)</strong></label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white"><strong>Rp</strong></span>
                                <input type="text" class="form-control bg-light fw-bold" id="final_total_display" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-tags"></i> Detail Tambahan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="project" class="form-label">Project</label>
                            <input type="text" class="form-control @error('project') is-invalid @enderror" 
                                   id="project" name="project" placeholder="Nama project" value="{{ old('project', $journal->project) }}">
                            @error('project')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company" class="form-label">Perusahaan</label>
                            <input type="text" class="form-control @error('company') is-invalid @enderror" 
                                   id="company" name="company" placeholder="Nama perusahaan" value="{{ old('company', $journal->company) }}">
                            @error('company')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="ket" class="form-label">Keterangan (Ket)</label>
                            <input type="text" class="form-control @error('ket') is-invalid @enderror" 
                                   id="ket" name="ket" placeholder="Kategori atau catatan" value="{{ old('ket', $journal->ket) }}">
                            @error('ket')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nota" class="form-label">Nota / Invoice <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nota') is-invalid @enderror" 
                                   id="nota" name="nota" placeholder="Nomor nota/invoice" value="{{ old('nota', $journal->nota) }}" required>
                            @error('nota')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-cogs"></i> Klasifikasi & Status</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="account_id" class="form-label">Klasifikasi Account <span class="text-danger">*</span></label>
                            <select class="form-select @error('account_id') is-invalid @enderror" id="account_id" name="account_id" required>
                                <option value="">-- Pilih Account --</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('account_id', $journal->account_id) == $account->id ? 'selected' : '' }}>
                                        {{ $account->category->code }}-{{ $account->code }} - {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('account_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipe Transaksi (IN/OUT) <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type_in" value="in" {{ old('type', $journal->type) == 'in' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="type_in">
                                    <i class="fas fa-arrow-down text-success"></i> IN (Masuk)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="type" id="type_out" value="out" {{ old('type', $journal->type) == 'out' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="type_out">
                                    <i class="fas fa-arrow-up text-danger"></i> OUT (Keluar)
                                </label>
                            </div>
                        </div>
                        @error('type')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <label class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_status" id="status_lunas" value="lunas" {{ old('payment_status', $journal->payment_status) == 'lunas' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="status_lunas">
                                    <i class="fas fa-check-circle text-success"></i> LUNAS
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_status" id="status_tidak_lunas" value="tidak_lunas" {{ old('payment_status', $journal->payment_status) == 'tidak_lunas' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="status_tidak_lunas">
                                    <i class="fas fa-exclamation-circle text-warning"></i> TIDAK LUNAS
                                </label>
                            </div>
                        </div>
                        @error('payment_status')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> <strong>Info Otomatis:</strong><br>
                    • <strong>OUT + TIDAK LUNAS</strong> = HUTANG (uang yang harus dibayar)<br>
                    • <strong>IN + TIDAK LUNAS</strong> = PIUTANG (uang yang harus diterima)
                </div>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Update Jurnal
            </button>
            <a href="{{ route('jurnal.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const qtyInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price'); // Hidden field
    const priceDisplay = document.getElementById('price_display'); // Display field
    const taxCheckbox = document.getElementById('tax');
    const totalDisplay = document.getElementById('total_display');
    const ppnDisplay = document.getElementById('ppn_display');
    const finalTotalDisplay = document.getElementById('final_total_display');

    function calculateTotals() {
        // Remove any formatting from input and parse as number
        const qty = parseFloat(qtyInput.value.replace(/[^\d.-]/g, '')) || 0;
        const price = parseFloat(priceInput.value.replace(/[^\d.-]/g, '')) || 0;
        const hasTax = taxCheckbox.checked;

        console.log('Qty:', qty, 'Price:', price); // Debug

        // Calculate total (before tax)
        const total = qty * price;

        // Calculate PPN (11% if checked)
        const ppn = hasTax ? (total * 0.11) : 0;

        // Calculate final total
        const finalTotal = total + ppn;

        console.log('Total:', total, 'PPN:', ppn, 'Final:', finalTotal); // Debug

        // Display with formatting
        totalDisplay.value = formatRupiah(total);
        ppnDisplay.value = formatRupiah(ppn);
        finalTotalDisplay.value = formatRupiah(finalTotal);
    }

    function formatRupiah(amount) {
        // Format number to Indonesian format: 1.000.000
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    function formatPriceInput() {
        // Get raw value without formatting
        let value = priceDisplay.value.replace(/[^\d]/g, '');
        
        if (value === '') {
            priceInput.value = '0';
            priceDisplay.value = '';
            return;
        }
        
        // Convert to number and format
        let numValue = parseInt(value);
        priceInput.value = numValue; // Store raw number in hidden field
        priceDisplay.value = formatRupiah(numValue); // Display formatted
        
        calculateTotals();
    }

    // Attach event listeners
    qtyInput.addEventListener('input', calculateTotals);
    priceDisplay.addEventListener('input', formatPriceInput);
    priceDisplay.addEventListener('blur', formatPriceInput);
    taxCheckbox.addEventListener('change', calculateTotals);

    // Initial calculation and format
    if (priceInput.value && priceInput.value !== '0') {
        priceDisplay.value = formatRupiah(parseInt(priceInput.value));
    }
    calculateTotals();
});
</script>
@endsection
