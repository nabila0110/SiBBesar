@extends('layouts.app')

@section('title', 'Dashboard - SiBBesar')

@section('content')

 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f4f7fc;
      color: #333;
      margin: 0;
      padding: 0;
    }
    header {
      background-color: #ffffff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      padding: 15px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    header h1 {
      font-size: 20px;
      color: #1a2b4b;
      margin: 0;
    }
    .container {
      max-width: 900px;
      background-color: #fff;
      margin: 40px auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      position: relative;
      z-index: 1;
    }
    h2 {
      text-align: center;
      color: #1a2b4b;
      margin-bottom: 20px;
    }
    .periode {
      margin-bottom: 20px;
      text-align: center;
    }
    input[type="text"] {
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
      width: 150px;
    }
    button {
      background-color: #dc3545;
      color: #fff;
      border: none;
      border-radius: 6px;
      padding: 8px 16px;
      margin-left: 10px;
      cursor: pointer;
      transition: 0.3s;
      z-index: 10;
      position: relative;
    }
    button:hover {
      background-color: #b02a37;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: #1a2b4b;
      color: #fff;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .tahun {
      text-align: center;
      margin-top: 10px;
      font-weight: 600;
      color: #1a2b4b;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Neraca Saldo Akhir</h2>

    <div class="periode">
      <label for="tahun">Pilih Tahun Periodik:</label>
      <input type="text" id="tahun" placeholder="2023">
      <button id="btnLihat">Lihat Neraca</button>
      <button id="btnCetak">Cetak PDF</button>
    </div>

    <div class="tahun">Tahun: 2023-12-31</div>

    <table id="neracaTable">
      <thead>
        <tr>
          <th>Nama Akun</th>
          <th>Debit</th>
          <th>Kredit</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>[1100] Kas Ditangan</td>
          <td>Rp. 50,000,000</td>
          <td>Rp. 0</td>
        </tr>
        <tr>
          <td>[1400] Persediaan Barang Dagang</td>
          <td>Rp. 1,000,000</td>
          <td>Rp. 0</td>
        </tr>
        <tr>
          <td>[4101] Pendapatan Penjualan</td>
          <td>Rp. 0</td>
          <td>Rp. 51,000,000</td>
        </tr>
        <tr>
          <td>[4102] Pendapatan Jasa</td>
          <td>Rp. 0</td>
          <td>Rp. 0</td>
        </tr>
      </tbody>
    </table>
  </div>

  <script>
    // Safe helper to get element by id
    function el(id) { return document.getElementById(id); }

    const btnLihat = el('btnLihat');
    const btnCetak = el('btnCetak');
    const inputTahun = el('tahun');
    const tahunDisplay = document.querySelector('.tahun');

    if (btnLihat) {
      btnLihat.addEventListener('click', function () {
        const tahun = inputTahun ? inputTahun.value.trim() : '';
        if (!tahun) {
          alert('Masukkan tahun terlebih dahulu!');
          return;
        }
        if (tahunDisplay) {
          tahunDisplay.textContent = `Tahun: ${tahun}-12-31`;
        }
        // Optionally: fetch data from server here and render rows
      });
    }

    if (btnCetak) {
      btnCetak.addEventListener('click', function () {
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
          const title = (inputTahun && inputTahun.value.trim()) ? `Neraca Saldo Akhir - ${inputTahun.value.trim()}` : 'Neraca Saldo Akhir';
          doc.setFontSize(16);
          doc.setFont(undefined, 'bold');
          doc.text(title, margin, 20);
          
          // Date
          doc.setFontSize(10);
          doc.setFont(undefined, 'normal');
          const today = new Date().toLocaleDateString('id-ID');
          doc.text(`Tanggal: ${today}`, margin, 28);
          
          // Table setup
          const table = document.querySelector('#neracaTable');
          const rows = [];
          const headers = ['Nama Akun', 'Debit', 'Kredit'];
          
          table.querySelectorAll('tbody tr').forEach(tr => {
            const cols = Array.from(tr.querySelectorAll('td')).map(td => td.innerText.trim());
            rows.push(cols);
          });

          // Column widths (in mm)
          const col1Width = 100; // Account name
          const col2Width = 42;  // Debit
          const col3Width = 42;  // Credit
          const colWidths = [col1Width, col2Width, col3Width];
          
          let y = 38;
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

          doc.save('Neraca_Saldo_Akhir.pdf');
        } catch (e) {
          console.error('jsPDF error:', e);
          alert('Error generating PDF: ' + e.message);
        }
      });
    }
  </script>
</body>

@endsection