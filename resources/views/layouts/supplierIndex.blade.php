@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Supplier Barang</h4>
        <a href="{{ route('supplier.create') }}" class="btn btn-primary">Tambah Supplier</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Supplier</th>
                    <th>Nama Supplier</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $s->kode_supplier }}</td>
                        <td>{{ $s->nama_supplier }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center">Belum ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
