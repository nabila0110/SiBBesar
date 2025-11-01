<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Neraca Saldo Awal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  <style>
    body {
      background-color: #f6f9fc;
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .card {
      border-radius: 10px;
    }
    .btn-danger {
      background-color: #dc3545;
      border: none;
    }
    .btn-danger:hover {
      background-color: #b02a37;
    }
    .table-light {
      background-color: #f1f1f1;
    }
    .btn-container {
      display: flex;
      justify-content: center;
      gap: 10px;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar px-4">
    <div class="container-fluid">
      <span class="navbar-brand fw-semibold">Keuangan</span>
      <span class="text-muted">Sedang Login</span>
    </div>
  </nav>

  <!-- Container -->
  <div class="container mt-4 mb-5">
    <h3 class="text-center fw-bold mb-4">Neraca Saldo Awal</h3>

    <!-- Filter Tahun -->
    <div class="card shadow-sm p-4 mb-4">
      <div class="row g-3 align-items-end">
        <div class="col-md-6">
          <label for="tahun" class="form-label fw-semibold">Pilih Tahun Periodik</label>
          <select class="form-select" id="tahun">
            <option value="2023">2023</option>
            <option value="2024">2024</option>
            <option value="2025">2025</option>
          </select>
        </div>
        <div class="col-md-6 btn-container">
          <button class="btn btn-danger" onclick="lihatNeracaSaldo()">Lihat Neraca Saldo</button>
          <button class="btn btn-danger" id="btnCetak">Cetak PDF</button>
        </div>
      </div>
    </div>

      <div class="table-responsive">
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr class="text-center">
              <th width="45%">Nama Akun</th>
              <th width="25%">Debit</th>
              <th width="25%">Kredit</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>[1100] Kas Ditangan</td>
              <td>Rp. 50.000.000</td>
              <td>Rp. 0</td>
            </tr>
            <tr>
              <td>[1400] Persediaan Barang Dagang</td>
              <td>Rp. 1.000.000</td>
              <td>Rp. 0</td>
            </tr>
            <tr>
              <td>[4101] Pendapatan Penjualan</td>
              <td>Rp. 0</td>
              <td>Rp. 51.000.000</td>
            </tr>
            <tr>
              <td>[4102] Pendapatan Jasa</td>
              <td>Rp. 0</td>
              <td>Rp. 20.000.000</td>
            </tr>
            <!-- sample total row -->
            <tr class="fw-bold">
              <td class="text-end">Total</td>
              <td>Rp. 51.000.000</td>
              <td>Rp. 71.000.000</td>
            </tr>
          </tbody>
        </table>
      </div>

    <!-- Selected year display -->
    <div id="tahunTerpilih" class="text-center mt-3 fw-semibold">Tahun: -</div>

  </div>

  <!-- Scripts -->
  <script>
    async function lihatNeracaSaldo() {
      const sel = document.getElementById('tahun');
      const tahun = sel ? sel.value : '';
      const out = document.getElementById('tahunTerpilih');
      if (out) out.textContent = 'Tahun: ' + tahun;

      // Fetch data from server API
      try {
        const res = await fetch(/api/neraca-saldo-awal?year=${encodeURIComponent(tahun)});
        if (!res.ok) throw new Error('HTTP ' + res.status);
        const json = await res.json();
        const rows = json.rows || [];

        const tbody = document.querySelector('.table.table-bordered tbody');
        if (!tbody) return;
        tbody.innerHTML = '';

        let totalDebit = 0;
        let totalCredit = 0;

        const fmt = v => 'Rp. ' + (Number(v) || 0).toLocaleString('id-ID');

        rows.forEach(r => {
          const tr = document.createElement('tr');
          const tdName = document.createElement('td');
          tdName.textContent = [${r.code}] ${r.name};
          const tdDebit = document.createElement('td');
          tdDebit.textContent = fmt(r.debit);
          const tdCredit = document.createElement('td');
          tdCredit.textContent = fmt(r.credit);
          tr.appendChild(tdName);
          tr.appendChild(tdDebit);
          tr.appendChild(tdCredit);
          tbody.appendChild(tr);

          totalDebit += Number(r.debit || 0);
          totalCredit += Number(r.credit || 0);
        });

        // append total row
        const trTotal = document.createElement('tr');
        trTotal.classList.add('fw-bold');
        const tdLabel = document.createElement('td');
        tdLabel.classList.add('text-end');
        tdLabel.textContent = 'Total';
        const tdTotalDebit = document.createElement('td');
        tdTotalDebit.textContent = fmt(totalDebit);
        const tdTotalCredit = document.createElement('td');
        tdTotalCredit.textContent = fmt(totalCredit);
        trTotal.appendChild(tdLabel);
        trTotal.appendChild(tdTotalDebit);
        trTotal.appendChild(tdTotalCredit);
        tbody.appendChild(trTotal);

        // Scroll to table for better UX
        const tableWrap = document.querySelector('.table-responsive');
        if (tableWrap) tableWrap.scrollIntoView({ behavior: 'smooth' });
      } catch (err) {
        console.error('Failed to load neraca saldo:', err);
        alert('Gagal memuat data neraca saldo: ' + (err.message || err));
      }
    }

    // Simple PDF export using jsPDF (basic, no autoTable plugin)
    document.getElementById('btnCetak')?.addEventListener('click', function () {
      const sel = document.getElementById('tahun');
      const tahun = sel ? sel.value : '';
      try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ unit: 'pt', format: 'a4' });
        const title = 'Neraca Saldo Awal - ' + tahun;
        doc.setFontSize(14);
        doc.text(title, 40, 40);

        const table = document.querySelector('.table.table-bordered');
        if (!table) {
          doc.text('Tidak ada data tabel.', 40, 80);
          doc.save('neraca-saldo-' + tahun + '.pdf');
          return;
        }

        const rows = [];
        table.querySelectorAll('tbody tr').forEach(tr => {
          const cols = Array.from(tr.querySelectorAll('td')).map(td => td.innerText.trim());
          rows.push(cols);
        });

        const startY = 70;
        const lineHeight = 18;
        rows.forEach((r, i) => {
          const y = startY + i * lineHeight;
          doc.text(r[0] || '', 40, y);
          doc.text(r[1] || '', 360, y);
          doc.text(r[2] || '', 460, y);
          if (y > 750) doc.addPage();
        });

        doc.save('neraca-saldo-awal-' + tahun + '.pdf');
      } catch (err) {
        console.error(err);
        alert('Gagal membuat PDF: ' + (err.message || err));
      }
    });
  </script>

</body>
</html>