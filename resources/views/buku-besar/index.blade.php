@extends('layouts.app')

@section('title', 'Buku Besar - SiBBesar')

<!-- jsPDF & autoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<style>
    .table-buku-besar {
        font-size: 0.85rem;
    }
    .table-buku-besar th {
        background-color: #4472C4;
        color: white;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
    }
    .account-header {
        background-color: #D9E1F2;
        font-weight: bold;
        padding: 8px;
    }
    .table-currency {
        text-align: right;
    }
    .subtotal-row {
        background-color: #F2F2F2;
        font-weight: bold;
    }
</style>

@section('content')
<div class="container-fluid mt-4">
    <h2 class="mb-4">Buku Besar</h2>

    <!-- Tombol Aksi -->
    <div class="mb-4 d-flex flex-wrap gap-2">
        <button class="btn btn-success" onclick="exportToExcel()">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
        <button class="btn btn-danger" onclick="cetakPDF()">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </button>
    </div>

    <!-- Filter Tanggal -->
    <form method="GET" action="{{ route('buku-besar.index') }}" class="row g-3 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" class="form-control" name="dari_tanggal" value="{{ request('dari_tanggal') }}" />
        </div>
        <div class="col-md-3">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" class="form-control" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}" />
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-info">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('buku-besar.index') }}" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </a>
        </div>
    </form>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 text-center">BUKU BESAR</h5>
        </div>
        <div class="card-body p-0">
            @php
                $groupedJournals = $journals->groupBy('account_id');
            @endphp

            @if($groupedJournals->count() > 0)
                @foreach($groupedJournals as $accountId => $accountJournals)
                    @php
                        $account = $accountJournals->first()->account;
                        $classification = $account ? ($account->code . ' - ' . $account->name) : 'Tanpa Klasifikasi';
                    @endphp
                    
                    <!-- Account Group Header -->
                    <div class="account-header">
                        BEBAN {{ strtoupper($classification) }}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-buku-besar mb-0" id="tabel-{{ $accountId }}">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>TANGGAL</th>
                                    <th>NOTA</th>
                                    <th>ITEM</th>
                                    <th>QTY</th>
                                    <th>SATUAN</th>
                                    <th>@</th>
                                    <th>TOTAL</th>
                                    <th>PPN 11%</th>
                                    <th>PROJECT</th>
                                    <th>PERUSAHAAN</th>
                                    <th>KET</th>
                                    <th>NOTA</th>
                                    <th>IN/OUT</th>
                                    <th>LUNAS/TIDAK LUNAS</th>
                                    <th>KLASIFIKASI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach($accountJournals as $journal)
                                    <tr>
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($journal->transaction_date)->format('d/m/Y') }}</td>
                                        <td>{{ $journal->nota }}</td>
                                        <td>{{ $journal->item }}</td>
                                        <td class="text-center">{{ number_format($journal->quantity, 0) }}</td>
                                        <td class="text-center">{{ $journal->satuan ?: '-' }}</td>
                                        <td class="table-currency">Rp {{ number_format($journal->price, 0, ',', '.') }}</td>
                                        <td class="table-currency">Rp {{ number_format($journal->total, 0, ',', '.') }}</td>
                                        <td class="table-currency">
                                            @if($journal->tax)
                                                Rp {{ number_format($journal->ppn_amount, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $journal->project ?: '-' }}</td>
                                        <td>{{ $journal->company ?: '-' }}</td>
                                        <td class="text-center">{{ $journal->ket ?: '-' }}</td>
                                        <td>{{ $journal->nota }}</td>
                                        <td class="text-center">
                                            @if($journal->type === 'in')
                                                <span class="badge bg-success">IN</span>
                                            @else
                                                <span class="badge bg-danger">OUT</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($journal->payment_status === 'lunas')
                                                <span class="badge bg-success">LUNAS</span>
                                            @else
                                                <span class="badge bg-warning text-dark">TIDAK LUNAS</span>
                                            @endif
                                        </td>
                                        <td><small>{{ $classification }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="subtotal-row">
                                <tr>
                                    <td colspan="7" class="text-end">TOTAL:</td>
                                    <td class="table-currency">Rp {{ number_format($accountJournals->sum('total'), 0, ',', '.') }}</td>
                                    <td class="table-currency">Rp {{ number_format($accountJournals->sum('ppn_amount'), 0, ',', '.') }}</td>
                                    <td colspan="7"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <br>
                @endforeach
            @else
                <div class="alert alert-info m-3">
                    <i class="fas fa-info-circle"></i> Belum ada data buku besar. <a href="{{ route('jurnal.create') }}">Tambah jurnal baru</a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function cetakPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
        orientation: "landscape",
        unit: "mm",
        format: "a4"
    });

    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();
    const margin = 15;

    // Add company logo
    const logo = new Image();
    logo.src = '/images/logo_wb.png';
    
    logo.onload = function() {
        let startY = 52;
        
        // Header
        doc.addImage(logo, 'PNG', margin, 10, 25, 25);
        doc.setFontSize(16);
        doc.setFont(undefined, 'bold');
        doc.text("PT MITRA FAJAR KENCANA", pageWidth / 2, 15, { align: "center" });
        doc.setFontSize(10);
        doc.setFont(undefined, 'normal');
        doc.text("Jl. Contoh Alamat Perusahaan No. 123, Jakarta", pageWidth / 2, 21, { align: "center" });
        doc.text("Telp: (021) 1234-5678 | Email: info@mitrafajar.com", pageWidth / 2, 26, { align: "center" });
        doc.setLineWidth(0.5);
        doc.line(margin, 32, pageWidth - margin, 32);
        doc.setFontSize(14);
        doc.setFont(undefined, 'bold');
        doc.text("BUKU BESAR", pageWidth / 2, 40, { align: "center" });
        doc.setFontSize(9);
        doc.setFont(undefined, 'normal');
        const today = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
        doc.text(`Tanggal Cetak: ${today}`, pageWidth / 2, 46, { align: "center" });

        @php
            $groupedJournals = $journals->groupBy('account_id');
        @endphp

        @foreach($groupedJournals as $accountId => $accountJournals)
            @php
                $account = $accountJournals->first()->account;
                $classification = $account ? ($account->code . ' - ' . $account->name) : 'Tanpa Klasifikasi';
            @endphp

            // Group header
            doc.setFontSize(11);
            doc.setFont(undefined, 'bold');
            doc.setFillColor(217, 225, 242);
            doc.rect(margin, startY, pageWidth - (2 * margin), 8, 'F');
            doc.text("BEBAN {{ strtoupper($classification) }}", margin + 2, startY + 5);
            startY += 10;

            // Table data
            const rows{{ $accountId }} = [];
            @foreach($accountJournals as $journal)
                rows{{ $accountId }}.push([
                    "{{ $loop->iteration }}",
                    "{{ \Carbon\Carbon::parse($journal->transaction_date)->format('d/m/Y') }}",
                    "{{ $journal->nota }}",
                    "{{ $journal->item }}",
                    "{{ number_format($journal->quantity, 0) }}",
                    "{{ $journal->satuan ?: '-' }}",
                    "Rp {{ number_format($journal->price, 0, ',', '.') }}",
                    "Rp {{ number_format($journal->total, 0, ',', '.') }}",
                    "{{ $journal->tax ? 'Rp ' . number_format($journal->ppn_amount, 0, ',', '.') : '-' }}",
                    "{{ $journal->project ?: '-' }}",
                    "{{ $journal->company ?: '-' }}",
                    "{{ $journal->ket ?: '-' }}",
                    "{{ $journal->nota }}",
                    "{{ $journal->type === 'in' ? 'IN' : 'OUT' }}",
                    "{{ $journal->payment_status === 'lunas' ? 'LUNAS' : 'TIDAK LUNAS' }}",
                    "{{ $classification }}"
                ]);
            @endforeach

            doc.autoTable({
                startY: startY,
                head: [["NO", "TANGGAL", "NOTA", "ITEM", "QTY", "SATUAN", "@", "TOTAL", "PPN 11%", 
                        "PROJECT", "PERUSAHAAN", "KET", "NOTA", "IN/OUT", "LUNAS/TDK", "KLASIFIKASI"]],
                body: rows{{ $accountId }},
                foot: [[
                    { content: 'TOTAL:', colSpan: 7, styles: { halign: 'right', fontStyle: 'bold' } },
                    "Rp {{ number_format($accountJournals->sum('total'), 0, ',', '.') }}",
                    "Rp {{ number_format($accountJournals->sum('ppn_amount'), 0, ',', '.') }}",
                    { content: '', colSpan: 7 }
                ]],
                theme: 'grid',
                styles: { fontSize: 6, cellPadding: 1.5 },
                headStyles: { fillColor: [68, 114, 196], textColor: 255, fontStyle: 'bold', halign: 'center' },
                footStyles: { fillColor: [240, 240, 240], fontStyle: 'bold' },
                columnStyles: {
                    0: { halign: 'center', cellWidth: 8 },
                    1: { halign: 'center', cellWidth: 18 },
                    7: { halign: 'right' },
                    8: { halign: 'right' }
                },
                margin: { left: margin, right: margin },
                didDrawPage: function (data) {
                    const footerY = pageHeight - 10;
                    doc.setFontSize(8);
                    doc.setFont(undefined, 'normal');
                    doc.text(`Dicetak oleh: {{ Auth::user()->name ?? 'Admin' }}`, margin, footerY);
                    doc.text(`Halaman ${data.pageNumber}`, pageWidth / 2, footerY, { align: 'center' });
                    doc.text(new Date().toLocaleString('id-ID'), pageWidth - margin, footerY, { align: 'right' });
                }
            });

            startY = doc.lastAutoTable.finalY + 10;
            
            @if(!$loop->last)
                if (startY > pageHeight - 50) {
                    doc.addPage();
                    startY = 20;
                }
            @endif
        @endforeach

        doc.save(`Buku_Besar_${today.replace(/\s/g, '_')}.pdf`);
    };
    
    logo.onerror = function() {
        alert('Logo tidak ditemukan. PDF akan dibuat tanpa logo.');
    };
}

function exportToExcel() {
    const wb = XLSX.utils.book_new();
    
    @php
        $groupedJournals = $journals->groupBy('account_id');
    @endphp

    @foreach($groupedJournals as $accountId => $accountJournals)
        @php
            $account = $accountJournals->first()->account;
            $classification = $account ? ($account->code . ' - ' . $account->name) : 'Tanpa Klasifikasi';
            $sheetName = substr(preg_replace('/[^A-Za-z0-9 ]/', '', $classification), 0, 31);
        @endphp

        const header{{ $accountId }} = ["BEBAN {{ strtoupper($classification) }}"];
        const emptyRow = [];
        const tableHeaders{{ $accountId }} = ["NO", "TANGGAL", "NOTA", "ITEM", "QTY", "SATUAN", "@", "TOTAL", 
                                               "PPN 11%", "PROJECT", "PERUSAHAAN", "KET", "NOTA", "IN/OUT", 
                                               "LUNAS/TIDAK LUNAS", "KLASIFIKASI"];
        
        const dataRows{{ $accountId }} = [];
        @foreach($accountJournals as $journal)
            dataRows{{ $accountId }}.push([
                {{ $loop->iteration }},
                "{{ \Carbon\Carbon::parse($journal->transaction_date)->format('d/m/Y') }}",
                "{{ $journal->nota }}",
                "{{ $journal->item }}",
                {{ $journal->quantity }},
                "{{ $journal->satuan ?: '-' }}",
                "Rp {{ number_format($journal->price, 0, ',', '.') }}",
                "Rp {{ number_format($journal->total, 0, ',', '.') }}",
                "{{ $journal->tax ? 'Rp ' . number_format($journal->ppn_amount, 0, ',', '.') : '-' }}",
                "{{ $journal->project ?: '-' }}",
                "{{ $journal->company ?: '-' }}",
                "{{ $journal->ket ?: '-' }}",
                "{{ $journal->nota }}",
                "{{ $journal->type === 'in' ? 'IN' : 'OUT' }}",
                "{{ $journal->payment_status === 'lunas' ? 'LUNAS' : 'TIDAK LUNAS' }}",
                "{{ $classification }}"
            ]);
        @endforeach

        const totalRow{{ $accountId }} = [
            "", "", "", "", "", "", "TOTAL:",
            "Rp {{ number_format($accountJournals->sum('total'), 0, ',', '.') }}",
            "Rp {{ number_format($accountJournals->sum('ppn_amount'), 0, ',', '.') }}",
            "", "", "", "", "", "", ""
        ];

        const wsData{{ $accountId }} = [
            header{{ $accountId }},
            emptyRow,
            tableHeaders{{ $accountId }},
            ...dataRows{{ $accountId }},
            totalRow{{ $accountId }}
        ];

        const ws{{ $accountId }} = XLSX.utils.aoa_to_sheet(wsData{{ $accountId }});
        ws{{ $accountId }}['!cols'] = [
            { wch: 5 }, { wch: 12 }, { wch: 15 }, { wch: 25 }, { wch: 8 }, 
            { wch: 10 }, { wch: 15 }, { wch: 15 }, { wch: 15 }, { wch: 20 },
            { wch: 20 }, { wch: 15 }, { wch: 15 }, { wch: 10 }, { wch: 15 }, { wch: 25 }
        ];

        XLSX.utils.book_append_sheet(wb, ws{{ $accountId }}, "{{ $sheetName }}");
    @endforeach

    XLSX.writeFile(wb, `Buku_Besar_${new Date().toISOString().slice(0,10)}.xlsx`);
}
</script>

@endsection