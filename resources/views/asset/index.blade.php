@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <h3 class="fw-bold mb-4">Daftar Aset</h3>

    <div class="card shadow-sm p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAset">
                + Tambah Aset Baru
            </button>
        </div>

        <!-- Tabel Aset -->
        <div class="table-responsive">
            <table id="tabelAset" class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>No</th>
                        <th>Nama Aset</th>
                        <th>Tanggal Perolehan</th>
                        <th>Nomor Aset</th>
                        <th>Tingkat Penyusutan (%)</th>
                        <th>Harga Perolehan</th>
                        <th>Akumulasi Penyusutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assets as $index => $asset)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $asset->asset_name }}</td>
                        <td class="text-center">{{ $asset->purchase_date }}</td>
                        <td class="text-center">{{ $asset->asset_no }}</td>
                        <td class="text-center">{{ number_format($asset->depreciation_rate, 1) }}%</td>
                        <td class="text-end">Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($asset->accumulated_depreciation, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning edit-asset" data-id="{{ $asset->id }}">
                                Edit
                            </button>
                            <button class="btn btn-sm btn-danger delete-asset" data-id="{{ $asset->id }}">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Aset -->
<div class="modal fade" id="modalTambahAset" tabindex="-1" aria-labelledby="modalTambahAsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="modalTambahAsetLabel">Tambah Aset Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formAset" action="{{ route('asset.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Aset</label>
                            <input type="text" class="form-control" name="asset_name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Perolehan</label>
                            <input type="date" class="form-control" name="purchase_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Akun Aset</label>
                            <select class="form-control" name="account_id" required>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Harga Perolehan</label>
                            <input type="text" class="form-control currency-input" name="purchase_price" placeholder="Rp" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tingkat Penyusutan (%)</label>
                            <input type="number" class="form-control" name="depreciation_rate" min="0" max="100" step="0.01" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Akumulasi Penyusutan</label>
                            <input type="text" class="form-control currency-input" name="accumulated_depreciation" placeholder="Rp" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="simpanAset">Simpan</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/asset.js') }}"></script>
@endpush

@push('styles')
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
    .card {
        border-radius: 10px;
    }
    .btn-primary {
        background-color: #0d6efd;
        border: none;
    }
    .btn-primary:hover {
        background-color: #0b5ed7;
    }
</style>
@endpush

@endsection