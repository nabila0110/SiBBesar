 <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- jsPDF & autoTable -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

@extends('layouts.app')

@section('content')
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- jsPDF & autoTable -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

  <style>
    body { background-color: #f2f6ff; }
    .container-box {
      background: #fff;
      border-radius: 12px;
      padding: 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    .btn-tambah {
      background-color: #0d6efd;
      color: #fff;
      border: none;
    }
    .btn-jurnal {
      background-color: #dc3545;
      color: #fff;
      border: none;
    }
    table th, table td { vertical-align: middle; }
  </style>
</head>
<body>
<div class="container my-5">
  <!-- Halaman Jurnal Umum -->
  <div class="container-box" id="halaman-jurnal">
    <h4 class="fw-bold mb-4 text-primary">Jurnal Umum</h4>

    <div class="mb-4 d-flex flex-wrap gap-2">
      <button class="btn btn-tambah" onclick="bukaHalaman('tambah-jurnal')">+ Tambah Jurnal</button>
      <button class="btn btn-tambah" onclick="bukaHalaman('tambah-penyesuaian')">+ Tambah Penyesuaian</button>
      <button class="btn btn-success" onclick="cetakPDF()">🖨 Cetak PDF</button>
    </div>

    <form class="row g-3 align-items-end mb-4">
      <div class="col-md-3">
        <label class="form-label">Dari Tanggal</label>
        <input type="text" class="form-control" id="dariTanggal" placeholder="dd/mm/yyyy" />
      </div>
      <div class="col-md-3">
        <label class="form-label">Sampai Tanggal</label>
        <input type="text" class="form-control" id="sampaiTanggal" placeholder="dd/mm/yyyy" />
      </div>
      <div class="col-md-3">
        <button type="button" class="btn btn-jurnal" onclick="tampilkanJurnal()">Tampilkan Jurnal</button>
      </div>
    </form>

    <h5 class="fw-bold text-center mb-2 mt-4">DAFTAR JURNAL</h5>
    <div class="table-responsive">
      <table class="table table-bordered" id="tabelJurnal">
        <thead class="table-light">
          <tr>
            <th>Tanggal</th>
            <th>Nomor Bukti</th>
            <th>Keterangan</th>
            <th>Nama Akun</th>
            <th>YOL</th>
            <th>SAT</th>
            <th>Harga Satuan</th>
            <th>Kode Akun</th>
            <th>Hutang/Piutang</th>
            <th>Debit</th>
            <th>Kredit</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- Halaman Tambah Jurnal -->
  <div class="container-box d-none" id="halaman-tambah-jurnal">
    <h4 class="fw-bold text-primary mb-4">Tambah Jurnal</h4>
    <form id="formJurnal">
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Tanggal Transaksi</label>
          <input type="text" class="form-control" id="tanggal" placeholder="dd/mm/yyyy" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Nomor Bukti</label>
          <input type="text" class="form-control" id="bukti">
        </div>
        <div class="col-md-6">
          <label class="form-label">Keterangan</label>
          <input type="text" class="form-control" id="keterangan">
        </div>
        <div class="col-md-4">
          <label class="form-label">Nama Akun</label>
          <input type="text" class="form-control" id="akun">
        </div>
        <div class="col-md-2">
          <label class="form-label">YOL</label>
          <input type="text" class="form-control" id="yol">
        </div>
        <div class="col-md-2">
          <label class="form-label">SAT</label>
          <input type="text" class="form-control" id="sat">
        </div>
        <div class="col-md-2">
          <label class="form-label">Harga Satuan</label>
          <input type="number" class="form-control" id="harga">
        </div>
        <div class="col-md-2">
          <label class="form-label">Kode Akun</label>
          <input type="text" class="form-control" id="kode">
        </div>
        <div class="col-md-3">
          <label class="form-label">Hutang / Piutang</label>
          <input type="text" class="form-control" id="hp">
        </div>
        <div class="col-md-3">
          <label class="form-label">Debit</label>
          <input type="number" class="form-control" id="debit">
        </div>
        <div class="col-md-3">
          <label class="form-label">Kredit</label>
          <input type="number" class="form-control" id="kredit">
        </div>
      </div>
      <div class="mt-4">
        <button type="button" class="btn btn-primary" onclick="simpanJurnal('jurnal')">Simpan</button>
        <button type="button" class="btn btn-secondary" onclick="bukaHalaman('jurnal')">Kembali</button>
      </div>
    </form>
  </div>

  <!-- Halaman Tambah Penyesuaian -->
  <div class="container-box d-none" id="halaman-tambah-penyesuaian">
    <h4 class="fw-bold text-primary mb-4">Tambah Penyesuaian</h4>
    <form id="formPenyesuaian" action="{{ route('jurnal.store') }}" method="POST">
      @csrf
      <div class="row g-3">
        <div class="col-md-3">
          <label class="form-label">Tanggal Transaksi</label>
          <input type="text" class="form-control" id="tanggal2" name="tanggal" placeholder="dd/mm/yyyy" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Nomor Bukti</label>
          <input type="text" class="form-control" id="bukti2" name="bukti">
        </div>
        <div class="col-md-6">
          <label class="form-label">Keterangan</label>
          <input type="text" class="form-control" id="keterangan2" name="keterangan">
        </div>
        <div class="col-md-4">
          <label class="form-label">Nama Akun</label>
          <input type="text" class="form-control" id="akun2" name="akun">
        </div>
        <div class="col-md-2">
          <label class="form-label">YOL</label>
          <input type="text" class="form-control" id="yol2" name="yol">
        </div>
        <div class="col-md-2">
          <label class="form-label">SAT</label>
          <input type="text" class="form-control" id="sat2" name="sat">
        </div>
        <div class="col-md-2">
          <label class="form-label">Harga Satuan</label>
          <input type="number" class="form-control" id="harga2" name="harga">
        </div>
        <div class="col-md-2">
          <label class="form-label">Kode Akun</label>
          <input type="text" class="form-control" id="kode2" name="kode">
        </div>
        <div class="col-md-3">
          <label class="form-label">Hutang / Piutang</label>
          <input type="text" class="form-control" id="hp2" name="hp">
        </div>
        <div class="col-md-3">
          <label class="form-label">Debit</label>
          <input type="number" class="form-control" id="debit2" name="debit">
        </div>
        <div class="col-md-3">
          <label class="form-label">Kredit</label>
          <input type="number" class="form-control" id="kredit2" name="kredit">
        </div>
      </div>
      <div class="mt-4">
        <button type="button" class="btn btn-primary" onclick="simpanJurnal('penyesuaian')">Simpan</button>
        <button type="button" class="btn btn-secondary" onclick="bukaHalaman('jurnal')">Kembali</button>
      </div>
    </form>
  </div>
</div>

<script>
function bukaHalaman(hal) {
  document.querySelectorAll('.container-box').forEach(div => div.classList.add('d-none'));
  if (hal === 'tambah-jurnal') document.getElementById('halaman-tambah-jurnal').classList.remove('d-none');
  else if (hal === 'tambah-penyesuaian') document.getElementById('halaman-tambah-penyesuaian').classList.remove('d-none');
  else document.getElementById('halaman-jurnal').classList.remove('d-none');
}

function simpanJurnal(type) {
  // If penyesuaian, submit to server via AJAX and append returned journal to table
  if (type === 'penyesuaian') {
    const form = document.getElementById('formPenyesuaian');
    const url = form.getAttribute('action');
    const formData = new FormData(form);
    // mark this as adjustment (optional flag)
    formData.append('type', 'penyesuaian');

    fetch(url, {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
      },
      body: formData
    })
    .then(response => response.json())
    .then(json => {
      if (json.status === 'success') {
        // append new journal row to table
        const journal = json.data;
        const detail = (journal.details && journal.details.length) ? journal.details[0] : null;
        const tbody = document.querySelector('#tabelJurnal tbody');
        const row = `<tr>
          <td>${journal.transaction_date ?? ''}</td>
          <td>${journal.journal_no ?? ''}</td>
          <td>${journal.description ?? ''}</td>
          <td>${detail && detail.account ? detail.account.name : ''}</td>
          <td></td>
          <td></td>
          <td>${detail ? (detail.debit || detail.credit) : ''}</td>
          <td>${detail && detail.account ? detail.account.code ?? '' : ''}</td>
          <td></td>
          <td>${detail ? detail.debit : ''}</td>
          <td>${detail ? detail.credit : ''}</td>
        </tr>`;
        tbody.insertAdjacentHTML('beforeend', row);
        alert('Penyesuaian berhasil ditambahkan.');
        bukaHalaman('jurnal');
      } else {
        alert('Gagal menyimpan penyesuaian: ' + (json.message || 'Validasi error'));
      }
    })
    .catch(err => {
      console.error(err);
      alert('Terjadi kesalahan saat menyimpan penyesuaian. Cek console.');
    });
    return;
  }

  // legacy localStorage behavior for 'jurnal' (client-only)
  const data = {
    tanggal: document.getElementById(type === 'jurnal' ? 'tanggal' : 'tanggal2').value,
    bukti: document.getElementById(type === 'jurnal' ? 'bukti' : 'bukti2').value,
    ket: document.getElementById(type === 'jurnal' ? 'keterangan' : 'keterangan2').value,
    akun: document.getElementById(type === 'jurnal' ? 'akun' : 'akun2').value,
    yol: document.getElementById(type === 'jurnal' ? 'yol' : 'yol2').value,
    sat: document.getElementById(type === 'jurnal' ? 'sat' : 'sat2').value,
    harga: document.getElementById(type === 'jurnal' ? 'harga' : 'harga2').value,
    kode: document.getElementById(type === 'jurnal' ? 'kode' : 'kode2').value,
    hp: document.getElementById(type === 'jurnal' ? 'hp' : 'hp2').value,
    debit: document.getElementById(type === 'jurnal' ? 'debit' : 'debit2').value,
    kredit: document.getElementById(type === 'jurnal' ? 'kredit' : 'kredit2').value
  };
  const key = type === 'jurnal' ? 'dataJurnal' : 'dataPenyesuaian';
  const arr = JSON.parse(localStorage.getItem(key)) || [];
  arr.push(data);
  localStorage.setItem(key, JSON.stringify(arr));
  alert("Data berhasil disimpan!");
  bukaHalaman('jurnal');
}

function tampilkanJurnal() {
  const tbody = document.querySelector('#tabelJurnal tbody');
  tbody.innerHTML = '';
  const data = JSON.parse(localStorage.getItem('dataJurnal')) || [];
  data.forEach(d => {
    const row = `<tr>
      <td>${d.tanggal}</td>
      <td>${d.bukti}</td>
      <td>${d.ket}</td>
      <td>${d.akun}</td>
      <td>${d.yol}</td>
      <td>${d.sat}</td>
      <td>${d.harga}</td>
      <td>${d.kode}</td>
      <td>${d.hp}</td>
      <td>${d.debit}</td>
      <td>${d.kredit}</td>
    </tr>`;
    tbody.insertAdjacentHTML('beforeend', row);
  });
}

function cetakPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF({ orientation: "landscape" });
  doc.setFontSize(14);
  doc.text("Jurnal Umum", 140, 15, { align: "center" });

  const data = JSON.parse(localStorage.getItem('dataJurnal')) || [];
  const rows = data.map(d => [
    d.tanggal, d.bukti, d.ket, d.akun, d.yol, d.sat, d.harga, d.kode, d.hp, d.debit, d.kredit
  ]);

  doc.autoTable({
    startY: 25,
    head: [["Tanggal", "No Bukti", "Keterangan", "Akun", "YOL", "SAT", "Harga", "Kode", "H/P", "Debit", "Kredit"]],
    body: rows,
    theme: 'grid',
    styles: { fontSize: 9 }
  });

  doc.save("Jurnal_Umum.pdf");
}
</script>

@endsection
