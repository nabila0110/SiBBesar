@extends('layouts.app')

@section('title', 'Jurnal Umum - SiBBesar')

@section('content')
<!-- jsPDF & autoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

<style>
  .table-currency { text-align: right; }
</style>

<div class="container-fluid mt-4">
  <h2 class="mb-4">Jurnal Umum</h2>

  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="fas fa-check-circle"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- TOMBOL -->
  <div class="mb-4 d-flex flex-wrap gap-2">
    <a href="{{ route('jurnal.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Tambah Jurnal
    </a>
    <button class="btn btn-success" onclick="cetakPDF()">
      <i class="fas fa-file-pdf"></i> Cetak PDF
    </button>
  </div>

  <!-- FILTER TANGGAL -->
  <form method="GET" action="{{ route('jurnal.index') }}" class="row g-3 align-items-end mb-4">
    <div class="col-md-3">
      <label class="form-label">Dari Tanggal</label>
      <input type="date" class="form-control" name="dari_tanggal" value="{{ request('dari_tanggal') }}" />
    </div>
    <div class="col-md-3">
      <label class="form-label">Sampai Tanggal</label>
      <input type="date" class="form-control" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}" />
    </div>
    <div class="col-md-3">
      <button type="submit" class="btn btn-danger">
        <i class="fas fa-search"></i> Tampilkan Jurnal
      </button>
      <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">
        <i class="fas fa-redo"></i> Reset
      </a>
    </div>
  </form>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0 text-center">DAFTAR JURNAL</h5>
    </div>
    <div class="card-body">
      @if($details->count() > 0)
        <div class="table-responsive">
          <table class="table table-bordered table-hover" id="tabelJurnal">
            <thead class="table-light">
              <tr>
                <th>Tanggal</th>
                <th>No. Bukti</th>
                <th>Keterangan</th>
                <th>Akun</th>
                <th>Kode</th>
                <th class="table-currency">Debit</th>
                <th class="table-currency">Kredit</th>
                <th>Status</th>
                <th width="120">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($details as $detail)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($detail->journal->transaction_date)->format('d/m/Y') }}</td>
                  <td>{{ $detail->journal->journal_no }}</td>
                  <td>{{ $detail->journal->description }}</td>
                  <td>{{ $detail->account?->name ?? '-' }}</td>
                  <td>{{ $detail->account?->code ?? '-' }}</td>
                  <td class="table-currency">{{ number_format($detail->debit, 2, ',', '.') }}</td>
                  <td class="table-currency">{{ number_format($detail->credit, 2, ',', '.') }}</td>
                  <td><span class="badge bg-info">{{ ucfirst($detail->journal->status) }}</span></td>
                  <td>
                    <a href="{{ route('jurnal.edit', $detail->journal->id) }}" class="btn btn-sm btn-warning" title="Edit">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('jurnal.destroy', $detail->journal->id) }}" method="POST" style="display:inline;">
                      @csrf @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus jurnal ini?')" title="Hapus">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr class="table-secondary fw-bold">
                <td colspan="5" class="text-end">TOTAL:</td>
                <td class="table-currency">
                  {{ number_format($details->sum('debit'), 2, ',', '.') }}
                </td>
                <td class="table-currency">
                  {{ number_format($details->sum('credit'), 2, ',', '.') }}
                </td>
                <td colspan="2"></td>
              </tr>
            </tfoot>
          </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
          {{ $details->links() }}
        </div>
      @else
        <div class="alert alert-info">
          <i class="fas fa-info-circle"></i> Belum ada jurnal. <a href="{{ route('jurnal.create') }}">Buat jurnal baru</a>
        </div>
      @endif
    </div>
  </div>
</div>

<script>
function cetakPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF({ orientation: "landscape" });
  
  // Title
  doc.setFontSize(16);
  doc.text("JURNAL UMUM", doc.internal.pageSize.getWidth() / 2, 15, { align: "center" });
  
  // Subtitle
  doc.setFontSize(10);
  const today = new Date().toLocaleDateString('id-ID');
  doc.text(Tanggal Cetak: ${today}, 14, 25);

  // Ambil data dari tabel yang sudah di-render dari database
  const rows = [];
  document.querySelectorAll('#tabelJurnal tbody tr').forEach(tr => {
    const cells = tr.querySelectorAll('td');
    if (cells.length > 0) {
      rows.push([
        cells[0].textContent.trim(),
        cells[1].textContent.trim(),
        cells[2].textContent.trim(),
        cells[3].textContent.trim(),
        cells[4].textContent.trim(),
        cells[5].textContent.trim(),
        cells[6].textContent.trim(),
        cells[7].textContent.trim()
      ]);
    }
  });

  doc.autoTable({
    startY: 35,
    head: [["Tanggal", "No Bukti", "Keterangan", "Akun", "Kode", "Debit", "Kredit", "Status"]],
    body: rows,
    theme: 'grid',
    styles: { fontSize: 9, cellPadding: 3 },
    headStyles: { fillColor: [41, 128, 185], textColor: 255, fontStyle: 'bold' },
    columnStyles: {
      5: { halign: 'right' },
      6: { halign: 'right' }
    }
  });

  doc.save(Jurnal_Umum_${today.replace(/\//g, '-')}.pdf);
}
</script>

@endsection