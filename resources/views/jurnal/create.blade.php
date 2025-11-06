@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <h2 class="mb-4">Tambah Jurnal</h2>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('jurnal.store') }}" id="journalForm">
        @csrf
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Transaksi *</label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror" 
                           id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="bukti" class="form-label">No. Bukti</label>
                    <input type="text" class="form-control @error('bukti') is-invalid @enderror" 
                           id="bukti" name="bukti" placeholder="Otomatis jika dikosongkan" value="{{ old('bukti') }}">
                    @error('bukti')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                      id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Detail Jurnal</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="account_id" class="form-label">Akun *</label>
                            <select class="form-select @error('account_id') is-invalid @enderror" 
                                    id="account_id" name="account_id" required>
                                <option value="">-- Pilih Akun --</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}" @selected(old('account_id') == $account->id)>
                                        {{ $account->code }} - {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('account_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="debit" class="form-label">Debit (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control @error('debit') is-invalid @enderror" 
                                       id="debit" name="debit" placeholder="0" value="{{ old('debit') }}" 
                                       inputmode="numeric">
                            </div>
                            @error('debit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kredit" class="form-label">Kredit (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control @error('kredit') is-invalid @enderror" 
                                       id="kredit" name="kredit" placeholder="0" value="{{ old('kredit') }}" 
                                       inputmode="numeric">
                            </div>
                            @error('kredit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Jurnal
            </button>
            <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Setup currency formatting for debit and kredit fields
    setupCurrencyInputs();
});
</script>
@endsection
