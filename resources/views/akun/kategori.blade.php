@extends('layouts.app')

@section('title', 'Kelola Kategori Akun')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📁 Kelola Kategori Akun</h2>
        <div>
            <button type="button" class="btn btn-primary" id="btnTambahKategori">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </button>
            <a href="{{ route('akun.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Akun
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table id="tabelKategori" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Kategori</th>
                        <th>Tipe</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->code }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $category->type == 'asset' ? 'primary' : 
                                ($category->type == 'liability' ? 'warning' : 
                                ($category->type == 'equity' ? 'info' : 
                                ($category->type == 'revenue' ? 'success' : 'danger'))) 
                            }}">
                                {{ ucfirst($category->type) }}
                            </span>
                        </td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <span class="badge bg-{{ $category->is_active ? 'success' : 'secondary' }}">
                                {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $category->id }}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $category->id }}">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Kategori -->
<div class="modal fade" id="modalKategori" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKategoriTitle">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formKategori">
                @csrf
                <input type="hidden" id="kategori_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe Akun <span class="text-danger">*</span></label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">-- Pilih Tipe --</option>
                            <option value="asset">Asset (Aset)</option>
                            <option value="liability">Liability (Kewajiban)</option>
                            <option value="equity">Equity (Modal)</option>
                            <option value="revenue">Revenue (Pendapatan)</option>
                            <option value="expense">Expense (Beban)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="sub_number" class="form-label">Nomor Sub-Kategori <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="sub_number" name="sub_number" 
                               placeholder="Contoh: 1 untuk menghasilkan kode 1-1 (jika tipe Asset)" required>
                        <small class="text-muted">
                            Kode akan dibuat otomatis: Asset=1, Liability=2, Equity=3, Revenue=4, Expense=5
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="code_preview" class="form-label">Preview Kode</label>
                        <input type="text" class="form-control" id="code_preview" readonly 
                               placeholder="Pilih tipe dan masukkan nomor sub-kategori">
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               placeholder="Contoh: Aset Lancar" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Deskripsi kategori (opsional)"></textarea>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                        <label class="form-check-label" for="is_active">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanKategori">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
console.log('Kategori.blade.php scripts loaded!');

$(document).ready(function() {
    console.log('Document ready - kategori page');
    
    // Initialize DataTable
    $('#tabelKategori').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        order: [[0, 'asc']]
    });

    // Generate code preview
    function updateCodePreview() {
        const type = $('#type').val();
        const subNumber = $('#sub_number').val();
        
        if (type && subNumber) {
            const typeMap = {
                'asset': '1',
                'liability': '2',
                'equity': '3',
                'revenue': '4',
                'expense': '5'
            };
            const code = typeMap[type] + '-' + subNumber;
            $('#code_preview').val(code);
        } else {
            $('#code_preview').val('');
        }
    }

    $('#type, #sub_number').on('change keyup', updateCodePreview);

    // Tambah Kategori
    $('#btnTambahKategori').click(function() {
        console.log('Tambah kategori clicked');
        $('#modalKategoriTitle').text('Tambah Kategori');
        $('#formKategori')[0].reset();
        $('#kategori_id').val('');
        $('#code_preview').val('');
        $('#modalKategori').modal('show');
    });

    // Submit Form
    $('#formKategori').submit(function(e) {
        e.preventDefault();
        console.log('Form submitted');
        
        const id = $('#kategori_id').val();
        const url = id ? `/akun/kategori/${id}` : '/akun/kategori';
        const method = id ? 'PUT' : 'POST';
        
        const type = $('#type').val();
        const subNumber = $('#sub_number').val();
        const typeMap = {
            'asset': '1',
            'liability': '2',
            'equity': '3',
            'revenue': '4',
            'expense': '5'
        };
        const code = typeMap[type] + '-' + subNumber;

        const formData = {
            _token: $('input[name="_token"]').val(),
            code: code,
            type: type,
            name: $('#name').val(),
            description: $('#description').val(),
            is_active: $('#is_active').is(':checked') ? 1 : 0
        };

        if (method === 'PUT') {
            formData._method = 'PUT';
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            success: function(response) {
                console.log('Success:', response);
                $('#modalKategori').modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: id ? 'Kategori berhasil diperbarui' : 'Kategori berhasil ditambahkan',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                
                let errorMsg = 'Terjadi kesalahan saat menyimpan kategori';
                if (xhr.responseJSON?.errors) {
                    const errors = xhr.responseJSON.errors;
                    errorMsg = Object.values(errors).flat().join('<br>');
                } else if (xhr.responseJSON?.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    html: errorMsg
                });
            }
        });
    });

    // Edit Kategori
    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        console.log('Edit kategori:', id);
        
        $.get(`/akun/kategori/${id}`, function(data) {
            $('#modalKategoriTitle').text('Edit Kategori');
            $('#kategori_id').val(data.id);
            
            // Parse code (format: X-Y)
            const codeParts = data.code.split('-');
            const typeMap = {
                '1': 'asset',
                '2': 'liability',
                '3': 'equity',
                '4': 'revenue',
                '5': 'expense'
            };
            
            $('#type').val(typeMap[codeParts[0]]);
            $('#sub_number').val(codeParts[1]);
            $('#name').val(data.name);
            $('#description').val(data.description);
            $('#is_active').prop('checked', data.is_active);
            
            updateCodePreview();
            $('#modalKategori').modal('show');
        });
    });

    // Delete Kategori
    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus kategori ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/akun/kategori/${id}`,
                    method: 'POST',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        console.log('Deleted:', response);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Kategori berhasil dihapus',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Gagal menghapus kategori'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
