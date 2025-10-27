@extends('layouts.app')

@section('content')
<div style="padding:24px;">
    <h1>Daftar Akun</h1>
    <table style="width:100%;border-collapse:collapse;margin-top:16px;">
        <thead>
            <tr>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Kode</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Nama</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Tipe</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Saldo Debit</th>
                <th style="text-align:left;padding:8px;border-bottom:1px solid #e5e7eb;">Saldo Kredit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $acct)
                <tr>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6;">{{ $acct->code }}</td>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6;">{{ $acct->name }}</td>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6;">{{ $acct->type }}</td>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6;">{{ number_format($acct->balance_debit ?? 0,2,',','.') }}</td>
                    <td style="padding:8px;border-bottom:1px solid #f3f4f6;">{{ number_format($acct->balance_credit ?? 0,2,',','.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="padding:8px;">Tidak ada akun.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
<link rel="stylesheet" href="akun.css">
@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-primary">Daftar Akun</h3>
    <div>
      <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#bukaSaldoModalGlobal">+ Buka Saldo</button>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#buatAkunModal">+ Buat Akun</button>
    </div>
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table id="akunTable" class="table table-striped table-bordered" style="width:100%">
        <thead class="table-light">
          <tr>
            <th style="width:50px">No</th>
            <th style="width:110px">Kode</th>
            <th>Nama Akun</th>
            <th style="width:140px">Kelompok</th>
            <th style="width:120px">Tipe</th>
            <th style="width:220px">Tindakan</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td></td>
            <td>[1100]</td>
            <td>Kas Ditangan</td>
            <td>Assets</td>
            <td>Lancar</td>
            <td>
              <button class="btn btn-bukasaldo btn-sm me-1 open-saldo">Buka Saldo</button>
              <button class="btn btn-edit btn-sm me-1 edit-row">Edit</button>
              <button class="btn btn-hapus btn-sm delete-row">Hapus</button>
            </td>
          </tr>
          <tr>
            <td></td>
            <td>[1200]</td>
            <td>Piutang Usaha</td>
            <td>Assets</td>
            <td>Lancar</td>
            <td>
              <button class="btn btn-bukasaldo btn-sm me-1 open-saldo">Buka Saldo</button>
              <button class="btn btn-edit btn-sm me-1 edit-row">Edit</button>
              <button class="btn btn-hapus btn-sm delete-row">Hapus</button>
            </td>
          </tr>
          <tr>
            <td></td>
            <td>[1300]</td>
            <td>Perlengkapan</td>
            <td>Assets</td>
            <td>Lancar</td>
            <td>
              <button class="btn btn-bukasaldo btn-sm me-1 open-saldo">Buka Saldo</button>
              <button class="btn btn-edit btn-sm me-1 edit-row">Edit</button>
              <button class="btn btn-hapus btn-sm delete-row">Hapus</button>
            </td>
          </tr>
          <!-- Tambahkan baris lain sesuai kebutuhan -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Buat Akun -->
<div class="modal fade" id="buatAkunModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formAkun">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Akun</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Kode Akun:</label>
            <input type="text" id="kodeAkun" class="form-control" placeholder="[1500]" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Akun:</label>
            <input type="text" id="namaAkun" class="form-control" placeholder="Contoh: Petty Cash" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Kelompok Akun:</label>
            <select id="kelompok" class="form-select">
              <option>Assets</option><option>Liabilities</option><option>Equity</option>
              <option>Revenue</option><option>Expense</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Tipe Akun:</label>
            <select id="tipe" class="form-select">
              <option>Lancar (Current)</option><option>Tetap (Fixed)</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Beban (opsional):</label>
            <input type="text" id="jenisBeban" class="form-control" placeholder="Beban Kas">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan Akun</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Pembukaan Saldo (dipanggil per akun) -->
<div class="modal fade" id="bukaSaldoModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formSaldo">
        <div class="modal-header">
          <h5 class="modal-title">Pembukaan Saldo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Akun:</label>
            <input type="text" id="saldoNamaAkun" class="form-control" readonly>
            <input type="hidden" id="saldoKodeAkun">
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Transaksi Akun:</label>
            <select id="saldoJenis" class="form-select">
              <option>Debit</option><option>Kredit</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Jumlah:</label>
            <input type="number" id="saldoJumlah" class="form-control" placeholder="Masukkan jumlah">
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal:</label>
            <input type="date" id="saldoTanggal" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Keterangan:</label>
            <input type="text" id="saldoKeterangan" class="form-control" placeholder="Keterangan">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Buka Saldo Global (jika pengguna tekan tombol +Buka Saldo di header) -->
<div class="modal fade" id="bukaSaldoModalGlobal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formSaldoGlobal">
        <div class="modal-header">
          <h5 class="modal-title">Pembukaan Saldo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Pilih Akun:</label>
            <select id="selectAkunGlobal" class="form-select">
              <option value="[1100]|Kas Ditangan">[1100] Kas Ditangan</option>
              <option value="[1200]|Piutang Usaha">[1200] Piutang Usaha</option>
              <option value="[1300]|Perlengkapan">[1300] Perlengkapan</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Transaksi Akun:</label>
            <select id="saldoJenisGlobal" class="form-select"><option>Debit</option><option>Kredit</option></select>
          </div>
          <div class="mb-3">
            <label class="form-label">Jumlah:</label>
            <input type="number" id="saldoJumlahGlobal" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Tanggal:</label>
            <input type="date" id="saldoTanggalGlobal" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Tutup</button>
          <button class="btn btn-success" type="submit">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<footer>
  <p>2025 © Akuntansi :) | Crafted with ❤ by Geeveloper</p>
</footer>

<!-- Scripts -->
<script>akun.js</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
@endforeach
</ul>
@endif
@endsection
