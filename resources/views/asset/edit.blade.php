@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-4">Edit Aset</h3>

    <div class="card p-4">
        <form action="{{ route('asset.update', $asset->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Aset</label>
                    <input type="text" name="asset_name" class="form-control" value="{{ old('asset_name', $asset->asset_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Perolehan</label>
                    <input type="text" name="purchase_date" class="form-control" placeholder="dd/mm/yyyy" value="{{ old('purchase_date', $asset->purchase_date ? \Illuminate\Support\Carbon::parse($asset->purchase_date)->format('d/m/Y') : '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Akun Aset</label>
                    <select name="account_id" class="form-control" required>
                        @foreach(\App\Models\Account::where('type','asset')->orderBy('name')->get() as $account)
                            <option value="{{ $account->id }}" {{ $account->id == old('account_id', $asset->account_id) ? 'selected' : '' }}>{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-control">{{ old('description', $asset->description) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Harga Perolehan</label>
                    <input type="text" name="purchase_price" class="form-control currency-input" value="Rp {{ number_format(old('purchase_price', $asset->purchase_price),0,',','.') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tingkat Penyusutan (%)</label>
                    <input type="number" name="depreciation_rate" class="form-control" step="0.01" min="0" max="100" value="{{ old('depreciation_rate', $asset->depreciation_rate) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Akumulasi Penyusutan</label>
                    <input type="text" name="accumulated_depreciation" class="form-control currency-input" value="Rp {{ number_format(old('accumulated_depreciation', $asset->accumulated_depreciation),0,',','.') }}" required>
                </div>

                <div class="col-12 mt-3">
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                    <a href="{{ route('asset.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/asset.js') }}"></script>
@endpush

@endsection