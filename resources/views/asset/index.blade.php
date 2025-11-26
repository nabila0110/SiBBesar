@extends('layouts.app')

@section('title', 'Dashboard - SiBBesar')

@section('content')

<!-- Container -->
  <div class="container mt-2 mb-5">
    <h3 class="fw-bold mb-4">Daftar Aset</h3>

    <div class="card shadow-sm p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahAset">
          + Tambah Aset Baru
        </button>
        
        <!-- Search Box -->
        <div class="d-flex align-items-center">
          <input type="text" id="searchAset" class="form-control" placeholder="Cari aset..." style="width: 300px;">
        </div>
      </div>

      <!-- Tabel Aset -->
      <div class="table-responsive">
        <table id="tabelAset" class="table table-bordered table-striped">
          <thead class="table-light">
            <tr class="text-center">
              <th>No</th>
              <th>Nama Aset</th>
              <th>Tanggal Perolehan</th>
              <th>Harga Perolehan</th>
              <th>Tarif Penyusutan (%)</th>
              <th>Tahun Kepemilikan</th>
              <th>Akumulasi Penyusutan</th>
              <th>Nilai Buku</th>
              <th>Lokasi</th>
              <th>Kondisi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="tabelAsetBody">
            @forelse($assets as $index => $asset)
            @php
              $yearsHeld = \Carbon\Carbon::parse($asset->purchase_date)->floatDiffInYears(\Carbon\Carbon::now());
              $accumulatedDepreciation = $asset->purchase_price * ($asset->depreciation_rate / 100) * $yearsHeld;
              $bookValue = $asset->purchase_price - $accumulatedDepreciation;
              $bookValue = max(0, $bookValue);
            @endphp
            <tr>
              <td class="text-center">{{ $assets->firstItem() + $index }}</td>
              <td>{{ $asset->asset_name }}</td>
              <td class="text-center">{{ \Carbon\Carbon::parse($asset->purchase_date)->format('d/m/Y') }}</td>
              <td class="text-end">Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</td>
              <td class="text-center">{{ number_format($asset->depreciation_rate, 2) }}%</td>
              <td class="text-center">{{ number_format($yearsHeld, 2) }} tahun</td>
              <td class="text-end">Rp {{ number_format($accumulatedDepreciation, 0, ',', '.') }}</td>
              <td class="text-end fw-bold">Rp {{ number_format($bookValue, 0, ',', '.') }}</td>
              <td>{{ $asset->location ?? '-' }}</td>
              <td class="text-center">{{ $asset->condition ?? '-' }}</td>
              <td class="text-center">
                @if($asset->status == 'active')
                  <span class="badge bg-success">Active</span>
                @elseif($asset->status == 'retired')
                  <span class="badge bg-warning">Retired</span>
                @else
                  <span class="badge bg-danger">Disposed</span>
                @endif
              </td>
              <td class="text-center">
                <button class="btn btn-sm btn-warning btn-edit" 
                        data-id="{{ $asset->id }}"
                        data-bs-toggle="modal" 
                        data-bs-target="#modalEditAset">
                  <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-delete" 
                        data-id="{{ $asset->id }}"
                        data-name="{{ $asset->asset_name }}">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            @empty
            <tr id="emptyRow">
              <td colspan="12" class="text-center">Belum ada data aset</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      @if($assets->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3">
          <div class="text-muted small">
            Showing {{ $assets->firstItem() }} to {{ $assets->lastItem() }} of {{ $assets->total() }} results
          </div>
          <nav>
            <ul class="pagination mb-0" style="gap: 0.25rem;">
              @if($assets->onFirstPage())
                <li class="page-item disabled">
                  <span class="page-link" style="background-color: #6c757d; color: white; border-color: #6c757d;">Prev</span>
                </li>
              @else
                <li class="page-item">
                  <a class="page-link" href="{{ $assets->previousPageUrl() }}" style="background-color: #6c757d; color: white; border-color: #6c757d;">Prev</a>
                </li>
              @endif

              @foreach(range(1, $assets->lastPage()) as $page)
                @if($page == $assets->currentPage())
                  <li class="page-item active">
                    <span class="page-link" style="background-color: #007bff; border-color: #007bff;">{{ $page }}</span>
                  </li>
                @else
                  <li class="page-item">
                    <a class="page-link" href="{{ $assets->url($page) }}" style="background-color: #6c757d; color: white; border-color: #6c757d;">{{ $page }}</a>
                  </li>
                @endif
              @endforeach

              @if($assets->hasMorePages())
                <li class="page-item">
                  <a class="page-link" href="{{ $assets->nextPageUrl() }}" style="background-color: #6c757d; color: white; border-color: #6c757d;">Next</a>
                </li>
              @else
                <li class="page-item disabled">
                  <span class="page-link" style="background-color: #6c757d; color: white; border-color: #6c757d;">Next</span>
                </li>
              @endif
            </ul>
          </nav>
        </div>
      @endif
    </div>
  </div>

  <!-- Modal Tambah Aset Baru -->
  <div class="modal fade" id="modalTambahAset" tabindex="-1" aria-labelledby="modalTambahAsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-semibold" id="modalTambahAsetLabel">Tambah Aset Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formAset">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Aset <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="asset_name" id="namaAset" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal Perolehan <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="purchase_date" id="tanggalPerolehan" required>
              </div>
              <div class="col-md-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea class="form-control" name="description" id="description" rows="2"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Akun Aset <span class="text-danger">*</span></label>
  </div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

  // Format currency input
  function formatCurrency(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) {
      value = parseInt(value).toLocaleString('id-ID');
      input.value = 'Rp ' + value;
    } else {
      input.value = '';
    }
  }

  // Add event listeners for currency inputs
  document.getElementById('hargaPerolehan').addEventListener('input', function(e) {
    formatCurrency(e.target);
  });

  // Handle form submission
  document.getElementById('simpanAset').addEventListener('click', function() {
    const form = document.getElementById('formAset');
    
    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    const formData = new FormData(form);
    
    fetch('{{ route("asset.store") }}', {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
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

  // Handle delete
  document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const name = this.getAttribute('data-name');
      
      Swal.fire({
        title: 'Hapus Aset?',
        text: `Yakin ingin menghapus ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`/asset/${id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Content-Type': 'application/json'
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Terhapus!',
                text: data.message,
                confirmButtonText: 'OK'
              }).then(() => {
                location.reload();
              });
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Terjadi kesalahan saat menghapus data',
              confirmButtonText: 'OK'
            });
          });
        }
      });
    });
  });

  // Handle edit button
  document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      
      // Fetch asset data
      fetch(`/asset/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            const asset = data.data;
            
            // Populate form - pastikan element ada
            const editId = document.getElementById('editId');
            const editNamaAset = document.getElementById('editNamaAset');
            const editTanggalPerolehan = document.getElementById('editTanggalPerolehan');
            const editDescription = document.getElementById('editDescription');
            const editAccountId = document.getElementById('editAccountId');
            const editHargaPerolehan = document.getElementById('editHargaPerolehan');
            const editDepreciationRate = document.getElementById('editDepreciationRate');
            const editLocation = document.getElementById('editLocation');
            const editCondition = document.getElementById('editCondition');
            const editStatus = document.getElementById('editStatus');
            
            if (editId) editId.value = asset.id;
            if (editNamaAset) editNamaAset.value = asset.asset_name || '';
            if (editTanggalPerolehan) editTanggalPerolehan.value = asset.purchase_date || '';
            if (editDescription) editDescription.value = asset.description || '';
            if (editAccountId) editAccountId.value = asset.account_id || '';
            if (editHargaPerolehan) editHargaPerolehan.value = 'Rp ' + parseInt(asset.purchase_price || 0).toLocaleString('id-ID');
            if (editDepreciationRate) editDepreciationRate.value = asset.depreciation_rate || '';
            if (editLocation) editLocation.value = asset.location || '';
            if (editCondition) editCondition.value = asset.condition || '';
            if (editStatus) editStatus.value = asset.status || 'active';
            
            console.log('Data loaded:', asset);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal memuat data asset'
          });
        });
    });
  });

  // Handle update dengan event delegation
  document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'updateAset') {
      const form = document.getElementById('formEditAset');
      
      if (!form) {
        console.error('Form not found');
        return;
      }
      
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const id = document.getElementById('editId').value;
      
      if (!id) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'ID asset tidak ditemukan'
        });
        return;
      }
      
      const formData = new FormData(form);
      
      // Tambahkan _method untuk PUT
      formData.append('_method', 'PUT');
      
      // Format harga perolehan (hapus Rp dan titik)
      const hargaInput = document.getElementById('editHargaPerolehan');
      if (hargaInput && hargaInput.value) {
        const hargaValue = hargaInput.value.replace(/[^\d]/g, '');
        formData.set('purchase_price', hargaValue);
      }
      
      // Log untuk debugging
      console.log('Updating asset ID:', id);
      
      fetch(`/asset/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(response => {
        console.log('Response status:', response.status);
        return response.json();
      })
      .then(data => {
        console.log('Response data:', data);
        if (data.status === 'success') {
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditAset'));
          if (modal) modal.hide();
          
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
    }
  });

  // Format currency untuk edit form dengan event delegation
  document.addEventListener('input', function(e) {
    if (e.target && e.target.id === 'editHargaPerolehan') {
      formatCurrency(e.target);
    }
  });
</script>

  <div class="modal fade" id="modalTambahAset" tabindex="-1" aria-labelledby="modalTambahAsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-semibold" id="modalTambahAsetLabel">Tambah Aset Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formAset">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Aset <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="asset_name" id="namaAset" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal Perolehan <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="purchase_date" id="tanggalPerolehan" required>
              </div>
              <div class="col-md-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea class="form-control" name="description" id="description" rows="2"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Akun Aset <span class="text-danger">*</span></label>
                <select class="form-select" name="account_id" id="accountId" required>
                  <option value="">Pilih Akun</option>
                  @foreach($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->category->code }}-{{ $account->code }} - {{ $account->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Harga Perolehan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="purchase_price" id="hargaPerolehan" placeholder="Rp 0" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tarif Penyusutan (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="depreciation_rate" id="depreciationRate" step="0.01" min="0" max="100" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Akumulasi Penyusutan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="accumulated_depreciation" id="akumulasiPenyusutan" placeholder="Rp 0" required>
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

  <!-- Modal Edit Aset -->
  <div class="modal fade" id="modalEditAset" tabindex="-1" aria-labelledby="modalEditAsetLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-semibold" id="modalEditAsetLabel">Edit Aset</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="formEditAset">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="editId">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Aset <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="asset_name" id="editNamaAset" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal Perolehan <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="purchase_date" id="editTanggalPerolehan" required>
              </div>
              <div class="col-md-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea class="form-control" name="description" id="editDescription" rows="2"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Akun Aset <span class="text-danger">*</span></label>
                <select class="form-select" name="account_id" id="editAccountId" required>
                  <option value="">Pilih Akun</option>
                  @foreach($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->category->code }}-{{ $account->code }} - {{ $account->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Harga Perolehan <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="purchase_price" id="editHargaPerolehan" placeholder="Rp 0" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Tarif Penyusutan (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="depreciation_rate" id="editDepreciationRate" step="0.01" min="0" max="100" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Lokasi</label>
                <input type="text" class="form-control" name="location" id="editLocation" maxlength="15">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Kondisi</label>
                <select class="form-select" name="condition" id="editCondition">
                  <option value="">Pilih Kondisi</option>
                  <option value="Sangat Baik">Sangat Baik</option>
                  <option value="Baik">Baik</option>
                  <option value="Cukup">Cukup</option>
                  <option value="Kurang Baik">Kurang Baik</option>
                  <option value="Rusak">Rusak</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Status</label>
                <select class="form-select" name="status" id="editStatus">
                  <option value="active">Active</option>
                  <option value="retired">Retired</option>
                  <option value="disposed">Disposed</option>
                </select>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-primary" id="updateAset">Update</button>
        </div>
      </div>
    </div>
  </div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Search functionality - vanilla JavaScript
  document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchAset');
    const tableBody = document.getElementById('tabelAsetBody');
    
    if (searchInput && tableBody) {
      const rows = tableBody.getElementsByTagName('tr');
      
      searchInput.addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        
        for (let i = 0; i < rows.length; i++) {
          const row = rows[i];
          const text = row.textContent.toLowerCase();
          
          if (text.indexOf(searchValue) > -1) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        }
      });
    }
  });

  // Format currency input
  function formatCurrency(input) {
    let value = input.value.replace(/[^\d]/g, '');
    if (value) {
      value = parseInt(value).toLocaleString('id-ID');
      input.value = 'Rp ' + value;
    } else {
      input.value = '';
    }
  }

  // Add event listeners for currency inputs when document is ready
  document.addEventListener('DOMContentLoaded', function() {
    const hargaPerolehan = document.getElementById('hargaPerolehan');
    const akumulasiPenyusutan = document.getElementById('akumulasiPenyusutan');
    
    if (hargaPerolehan) {
      hargaPerolehan.addEventListener('input', function(e) {
        formatCurrency(e.target);
      });
    }
    
    if (akumulasiPenyusutan) {
      akumulasiPenyusutan.addEventListener('input', function(e) {
        formatCurrency(e.target);
      });
    }
  });

  // Handle form submission untuk tambah aset
  document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'simpanAset') {
      const form = document.getElementById('formAset');
      
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const formData = new FormData(form);
      
      // Format harga perolehan (hapus Rp dan titik)
      const hargaInput = document.getElementById('hargaPerolehan');
      if (hargaInput && hargaInput.value) {
        const hargaValue = hargaInput.value.replace(/[^\d]/g, '');
        formData.set('purchase_price', hargaValue);
      }
      
      // Format akumulasi penyusutan (hapus Rp dan titik)
      const akumulasiInput = document.getElementById('akumulasiPenyusutan');
      if (akumulasiInput && akumulasiInput.value) {
        const akumulasiValue = akumulasiInput.value.replace(/[^\d]/g, '');
        formData.set('accumulated_depreciation', akumulasiValue);
      }
      
      fetch('{{ route("asset.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
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
    }
  });

  // Handle delete dengan event delegation
  document.addEventListener('click', function(e) {
    if (e.target && e.target.closest('.btn-delete')) {
      const button = e.target.closest('.btn-delete');
      const id = button.getAttribute('data-id');
      const name = button.getAttribute('data-name');
      
      Swal.fire({
        title: 'Hapus Aset?',
        text: `Yakin ingin menghapus ${name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          fetch(`/asset/${id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Content-Type': 'application/json'
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              Swal.fire({
                icon: 'success',
                title: 'Terhapus!',
                text: data.message,
                confirmButtonText: 'OK'
              }).then(() => {
                location.reload();
              });
            }
          })
          .catch(error => {
            console.error('Error:', error);
            Swal.fire({
              icon: 'error',
              title: 'Error!',
              text: 'Terjadi kesalahan saat menghapus data',
              confirmButtonText: 'OK'
            });
          });
        }
      });
    }
  });

  // Handle edit button dengan event delegation
  document.addEventListener('click', function(e) {
    if (e.target && e.target.closest('.btn-edit')) {
      const button = e.target.closest('.btn-edit');
      const id = button.getAttribute('data-id');
      
      // Fetch asset data
      fetch(`/asset/${id}/edit`, {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            const asset = data.data;
            
            // Populate form
            const editId = document.getElementById('editId');
            const editNamaAset = document.getElementById('editNamaAset');
            const editTanggalPerolehan = document.getElementById('editTanggalPerolehan');
            const editDescription = document.getElementById('editDescription');
            const editAccountId = document.getElementById('editAccountId');
            const editHargaPerolehan = document.getElementById('editHargaPerolehan');
            const editDepreciationRate = document.getElementById('editDepreciationRate');
            const editLocation = document.getElementById('editLocation');
            const editCondition = document.getElementById('editCondition');
            const editStatus = document.getElementById('editStatus');
            
            if (editId) editId.value = asset.id;
            if (editNamaAset) editNamaAset.value = asset.asset_name || '';
            if (editTanggalPerolehan) editTanggalPerolehan.value = asset.purchase_date || '';
            if (editDescription) editDescription.value = asset.description || '';
            if (editAccountId) editAccountId.value = asset.account_id || '';
            if (editHargaPerolehan) editHargaPerolehan.value = 'Rp ' + parseInt(asset.purchase_price || 0).toLocaleString('id-ID');
            if (editDepreciationRate) editDepreciationRate.value = asset.depreciation_rate || '';
            if (editLocation) editLocation.value = asset.location || '';
            if (editCondition) editCondition.value = asset.condition || '';
            if (editStatus) editStatus.value = asset.status || 'active';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal memuat data asset'
          });
        });
    }
  });

  // Handle update dengan event delegation
  document.addEventListener('click', function(e) {
    if (e.target && e.target.id === 'updateAset') {
      const form = document.getElementById('formEditAset');
      
      if (!form) {
        console.error('Form not found');
        return;
      }
      
      if (!form.checkValidity()) {
        form.reportValidity();
        return;
      }

      const id = document.getElementById('editId').value;
      
      if (!id) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'ID asset tidak ditemukan'
        });
        return;
      }
      
      const formData = new FormData(form);
      
      // Tambahkan _method untuk PUT
      formData.append('_method', 'PUT');
      
      // Format harga perolehan (hapus Rp dan titik)
      const hargaInput = document.getElementById('editHargaPerolehan');
      if (hargaInput && hargaInput.value) {
        const hargaValue = hargaInput.value.replace(/[^\d]/g, '');
        formData.set('purchase_price', hargaValue);
      }
      
      fetch(`/asset/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditAset'));
          if (modal) modal.hide();
          
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
    }
  });

  // Format currency untuk edit form dengan event delegation
  document.addEventListener('input', function(e) {
    if (e.target && e.target.id === 'editHargaPerolehan') {
      formatCurrency(e.target);
    }
  });
</script>
