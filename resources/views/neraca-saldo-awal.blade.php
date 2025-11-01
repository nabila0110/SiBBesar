@extends('layouts.app')

@section('title', 'Dashboard - SiBBesar')

@section('content')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

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
        const res = await fetch(`/api/neraca-saldo-awal?year=${encodeURIComponent(tahun)}`);
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
          tdName.textContent = `[${r.code}] ${r.name}`;
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
        if (!jsPDF) {
          throw new Error('jsPDF not loaded');
        }
        
        const doc = new jsPDF({ unit: 'mm', format: 'a4' });
        const pageWidth = doc.internal.pageSize.getWidth();
        const pageHeight = doc.internal.pageSize.getHeight();
        const margin = 15;
        
        // Title
        doc.setFontSize(16);
        doc.setFont(undefined, 'bold');
        doc.text('Neraca Saldo Awal', margin, 20);
        
        // Year and Date
        doc.setFontSize(10);
        doc.setFont(undefined, 'normal');
        doc.text(`Tahun: ${tahun}`, margin, 28);
        const today = new Date().toLocaleDateString('id-ID');
        doc.text(`Tanggal: ${today}`, margin, 34);
        
        // Get table data
        const table = document.querySelector('.table.table-bordered');
        if (!table) {
          doc.text('Tidak ada data tabel.', margin, 50);
          doc.save('neraca-saldo-awal-' + tahun + '.pdf');
          return;
        }

        const rows = [];
        table.querySelectorAll('tbody tr').forEach(tr => {
          const cols = Array.from(tr.querySelectorAll('td')).map(td => td.innerText.trim());
          rows.push(cols);
        });

        // Column widths (in mm)
        const col1Width = 100; // Account name
        const col2Width = 42;  // Debit
        const col3Width = 42;  // Credit
        const headers = ['Nama Akun', 'Debit', 'Kredit'];
        
        let y = 44;
        const rowHeight = 7;
        const headerHeight = 8;
        
        // Draw header
        doc.setFontSize(11);
        doc.setFont(undefined, 'bold');
        doc.setFillColor(26, 43, 75); // Dark blue
        doc.setTextColor(255, 255, 255); // White text
        
        doc.rect(margin, y, col1Width, headerHeight, 'F');
        doc.rect(margin + col1Width, y, col2Width, headerHeight, 'F');
        doc.rect(margin + col1Width + col2Width, y, col3Width, headerHeight, 'F');
        
        doc.text(headers[0], margin + 2, y + 5);
        doc.text(headers[1], margin + col1Width + 2, y + 5);
        doc.text(headers[2], margin + col1Width + col2Width + 2, y + 5);
        
        y += headerHeight;
        
        // Draw rows
        doc.setFontSize(10);
        doc.setFont(undefined, 'normal');
        doc.setTextColor(0, 0, 0);
        
        let rowNum = 0;
        rows.forEach((r, index) => {
          // Check if need new page
          if (y + rowHeight > pageHeight - margin) {
            doc.addPage();
            y = margin;
            
            // Repeat header on new page
            doc.setFontSize(11);
            doc.setFont(undefined, 'bold');
            doc.setFillColor(26, 43, 75);
            doc.setTextColor(255, 255, 255);
            
            doc.rect(margin, y, col1Width, headerHeight, 'F');
            doc.rect(margin + col1Width, y, col2Width, headerHeight, 'F');
            doc.rect(margin + col1Width + col2Width, y, col3Width, headerHeight, 'F');
            
            doc.text(headers[0], margin + 2, y + 5);
            doc.text(headers[1], margin + col1Width + 2, y + 5);
            doc.text(headers[2], margin + col1Width + col2Width + 2, y + 5);
            
            y += headerHeight;
            doc.setFontSize(10);
            doc.setFont(undefined, 'normal');
            doc.setTextColor(0, 0, 0);
          }
          
          // Alternating row colors
          if (rowNum % 2 === 0) {
            doc.setFillColor(240, 240, 240);
            doc.rect(margin, y, col1Width + col2Width + col3Width, rowHeight, 'F');
          }
          
          // Check if it's a total row (bold)
          if (r[0] && r[0].toLowerCase().includes('total')) {
            doc.setFont(undefined, 'bold');
            doc.setFillColor(220, 220, 220);
            doc.rect(margin, y, col1Width + col2Width + col3Width, rowHeight, 'F');
          }
          
          // Draw row borders
          doc.setDrawColor(200, 200, 200);
          doc.rect(margin, y, col1Width, rowHeight);
          doc.rect(margin + col1Width, y, col2Width, rowHeight);
          doc.rect(margin + col1Width + col2Width, y, col3Width, rowHeight);
          
          // Draw text with right alignment for numbers
          doc.text(r[0] || '', margin + 2, y + 4);
          
          const debitText = r[1] || '';
          const creditText = r[2] || '';
          
          // Right-align numbers
          doc.text(debitText, margin + col1Width + col2Width - 2, y + 4, { align: 'right' });
          doc.text(creditText, margin + col1Width + col2Width + col3Width - 2, y + 4, { align: 'right' });
          
          doc.setFont(undefined, 'normal');
          y += rowHeight;
          rowNum++;
        });
        
        // Footer
        doc.setFontSize(9);
        doc.setTextColor(100, 100, 100);
        doc.text('Generated by SiBBesar - Sistem Informasi Buku Besar', margin, pageHeight - 10);

        doc.save('neraca-saldo-awal-' + tahun + '.pdf');
      } catch (err) {
        console.error('PDF generation error:', err);
        alert('Gagal membuat PDF: ' + (err.message || err));
      }
    });
  </script>

</body>
</html>
@endsection