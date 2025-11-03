@extends('layouts.app')

@section('title', 'Buku Besar - SiBBesar')

@push('styles')
<style>
  .btn-primary {
    background-color: #dc3545;
    border: none;
  }
  .btn-primary:hover {
    background-color: #c82333;
  }
</style>
@endpush

@section('content')
<div class="container mt-4">
  <h3 class="text-center fw-bold mb-4">Buku Besar</h3>

  <!-- Filter Periode -->
  <div class="card p-4 mb-4 shadow-sm">
    <div class="row g-3 align-items-end">
      <div class="col-md-4">
        <label for="dariTanggal" class="form-label">Dari Tanggal</label>
        <input type="text" class="form-control" id="dariTanggal" placeholder="dd/mm/yyyy" value="{{ $periode_from ?? '' }}">
      </div>
      <div class="col-md-4">
        <label for="sampaiTanggal" class="form-label">Sampai Tanggal</label>
        <input type="text" class="form-control" id="sampaiTanggal" placeholder="dd/mm/yyyy" value="{{ $periode_to ?? '' }}">
      </div>
      <div class="col-md-4 d-flex gap-2">
        <button class="btn btn-primary flex-grow-1" onclick="tampilkanBukuBesar()">Tampilkan Buku Besar</button>
        <button class="btn btn-success flex-grow-1" onclick="cetakPDF()">🖨 Cetak PDF</button>
      </div>
    </div>
  </div>

  <!-- Buku Besar -->
  <div class="card shadow-sm p-4" id="bukuBesar">
    <h5 class="text-center mb-3 fw-bold">BUKU BESAR</h5>
    <p class="text-center">Periode {{ $periode_from ?? '-' }} s/d {{ $periode_to ?? '-' }}</p>

    @if(empty($data) || count($data) == 0)
      <div class="alert alert-info">Tidak ada data untuk periode ini.</div>
    @else
      @foreach($data as $acct)
        @php
          $account = $acct['account'];
          $rows = $acct['rows'];
        @endphp

        <h6 class="fw-bold bg-light p-2 mt-3">[{{ $account->code }}] {{ $account->name }}</h6>
        <table class="table table-bordered">
          <thead class="table-light">
            <tr>
              <th>Tanggal</th>
              <th>Transaksi</th>
              <th>Debit</th>
              <th>Kredit</th>
              <th>Saldo</th>
            </tr>
          </thead>
          <tbody>
            @foreach($rows as $row)
              <tr>
                <td>{{ $row['tanggal'] }}</td>
                <td>{{ $row['transaksi'] }}</td>
                <td>
                  @if(!empty($row['debit']) && $row['debit'] > 0)
                    Rp. {{ number_format($row['debit'], 0, ',', '.') }}
                  @else
                    -
                  @endif
                </td>
                <td>
                  @if(!empty($row['kredit']) && $row['kredit'] > 0)
                    Rp. {{ number_format($row['kredit'], 0, ',', '.') }}
                  @else
                    -
                  @endif
                </td>
                <td>
                  @php $saldo = $row['saldo']; @endphp
                  @if($saldo < 0)
                    (Rp. {{ number_format(abs($saldo), 0, ',', '.') }})
                  @else
                    Rp. {{ number_format($saldo, 0, ',', '.') }}
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endforeach
    @endif
  </div>
</div>
@endsection

@push('scripts')
<!-- jsPDF dan AutoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<script>
  function tampilkanBukuBesar() {
    const dari = document.getElementById('dariTanggal').value;
    const sampai = document.getElementById('sampaiTanggal').value;
    let url = '{{ route("buku-besar.index") }}';
    const params = new URLSearchParams();
    if (dari) params.append('dari', dari);
    if (sampai) params.append('sampai', sampai);
    if (params.toString()) url += '?' + params.toString();
    window.location.href = url;
  }

  function cetakPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: "landscape" });
    doc.setFontSize(14);
    doc.text("BUKU BESAR", 140, 15, { align: "center" });

    const tables = document.querySelectorAll("#bukuBesar table");
    let yPos = 25;

    tables.forEach((table, index) => {
      const title = table.previousElementSibling.innerText;
      doc.setFontSize(12);
      doc.text(title, 15, yPos - 5);

      const rows = [];
      table.querySelectorAll("tbody tr").forEach(tr => {
        const cols = Array.from(tr.querySelectorAll("td")).map(td => td.innerText);
        rows.push(cols);
      });

      const headers = Array.from(table.querySelectorAll("thead th")).map(th => th.innerText);

      doc.autoTable({
        head: [headers],
        body: rows,
        startY: yPos,
        theme: 'grid',
        styles: { fontSize: 9 },
        margin: { left: 15, right: 15 }
      });

      yPos = doc.lastAutoTable.finalY + 15;
      if (index < tables.length - 1 && yPos > 180) {
        doc.addPage();
        yPos = 25;
      }
    });

    doc.save("Buku_Besar.pdf");
  }
</script>
@endpush