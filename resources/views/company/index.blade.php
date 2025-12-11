@extends('layouts.app')

@section('title', 'Data Perusahaan - SiBBesar')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-building"></i> Our Holding Company</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus"></i> Tambah Perusahaan
        </button>
    </div>

    @if($companies->count() > 0)
        <div class="row g-4">
            @foreach($companies as $company)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card company-card h-100 shadow-sm" style="cursor: pointer;" 
                         onclick="window.location.href='{{ route('company.ledger', $company->id) }}'">
                        <div class="card-body text-center">
                            <!-- Logo -->
                            <div class="company-logo mb-3">
                                @if($company->logo)
                                    <img src="{{ asset('images/companies/' . $company->logo) }}" 
                                         alt="{{ $company->name }}" 
                                         class="img-fluid rounded"
                                         style="max-height: 120px; object-fit: contain;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="height: 120px;">
                                        <i class="fas fa-building fa-4x text-muted"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Company Name -->
                            <h5 class="card-title fw-bold mb-2">{{ $company->name }}</h5>
                            
                            <!-- Company Code -->
                            <p class="text-muted small mb-2">Kode: {{ $company->code }}</p>

                            <!-- Description -->
                            @if($company->description)
                                <p class="card-text text-muted small mb-3">
                                    {{ \Str::limit($company->description, 80) }}
                                </p>
                            @endif

                            <!-- Info Tambahan -->
                            <div class="text-start small">
                                @if($company->phone)
                                    <p class="mb-1"><i class="fas fa-phone text-primary"></i> {{ $company->phone }}</p>
                                @endif
                                @if($company->email)
                                    <p class="mb-1"><i class="fas fa-envelope text-primary"></i> {{ $company->email }}</p>
                                @endif
                                <p class="mb-0">
                                    <i class="fas fa-file-alt text-primary"></i> 
                                    <strong>{{ $company->journals_count }}</strong> Transaksi
                                </p>
                            </div>

                            <!-- Status Badge -->
                            <div class="mt-3">
                                @if($company->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer dengan Action Buttons -->
                        <div class="card-footer bg-white border-top d-flex justify-content-between" 
                             onclick="event.stopPropagation();">
                            <button class="btn btn-sm btn-warning btn-edit" 
                                    data-id="{{ $company->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEdit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" 
                                    data-id="{{ $company->id }}"
                                    data-name="{{ $company->name }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Belum ada data perusahaan. 
            <a href="#" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah perusahaan baru</a>
        </div>
    @endif
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formTambah" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Tambah Perusahaan Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Logo</label>
                            <input type="file" class="form-control" name="logo" accept="image/*">
                            <small class="text-muted">Max 2MB (JPG, PNG, GIF)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" name="is_active">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea class="form-control" name="address" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telepon</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEdit" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="editId">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Perusahaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kode <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editCode" name="code" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Logo</label>
                            <input type="file" class="form-control" name="logo" accept="image/*">
                            <small class="text-muted">Max 2MB (JPG, PNG, GIF). Kosongkan jika tidak ingin mengubah.</small>
                            <div id="currentLogo" class="mt-2"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select class="form-select" id="editIsActive" name="is_active">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="2"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Alamat</label>
                            <textarea class="form-control" id="editAddress" name="address" rows="2"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telepon</label>
                            <input type="text" class="form-control" id="editPhone" name="phone">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.company-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.company-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2) !important;
    border-color: #0d6efd;
}

.company-logo img {
    transition: transform 0.3s ease;
}

.company-card:hover .company-logo img {
    transform: scale(1.05);
}

.card-footer button {
    transition: all 0.2s ease;
}

.card-footer button:hover {
    transform: scale(1.1);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form tambah
    document.getElementById('formTambah').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("company.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyimpan data',
                confirmButtonText: 'OK'
            });
        });
    });

    // Handle edit button click
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            
            // Fetch company data
            fetch(`/company/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    if (data.company) {
                        document.getElementById('editId').value = data.company.id;
                        document.getElementById('editCode').value = data.company.code;
                        document.getElementById('editName').value = data.company.name;
                        document.getElementById('editDescription').value = data.company.description || '';
                        document.getElementById('editAddress').value = data.company.address || '';
                        document.getElementById('editPhone').value = data.company.phone || '';
                        document.getElementById('editEmail').value = data.company.email || '';
                        document.getElementById('editIsActive').value = data.company.is_active ? '1' : '0';
                        
                        // Show current logo
                        if (data.company.logo) {
                            document.getElementById('currentLogo').innerHTML = 
                                `<img src="/images/companies/${data.company.logo}" style="max-height: 80px;" class="img-fluid">`;
                        } else {
                            document.getElementById('currentLogo').innerHTML = '';
                        }
                    }
                });
        });
    });

    // Handle form edit
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('editId').value;
        const formData = new FormData(this);
        
        fetch(`/company/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat mengupdate data',
                confirmButtonText: 'OK'
            });
        });
    });

    // Handle delete button
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            Swal.fire({
                title: 'Hapus Perusahaan?',
                text: `Yakin ingin menghapus ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/company/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: data.message,
                                confirmButtonText: 'OK'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: data.message,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
});
</script>
@endsection
