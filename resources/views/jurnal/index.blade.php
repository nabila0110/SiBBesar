@extends('layouts.app')

@section('title', 'Jurnal Umum - SiBBesar')

@section('content')

<!-- jsPDF & autoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<style>
    .table-currency {
        text-align: right;
    }

    .table-small {
        font-size: 0.85rem;
    }

    .badge-in {
        background-color: #28a745;
        color: white;
    }

    .badge-out {
        background-color: #dc3545;
        color: white;
    }

    .badge-hutang {
        background-color: #ffc107;
        color: #000;
    }

    .badge-piutang {
        background-color: #17a2b8;
        color: white;
    }
    
    /* Perbaikan untuk table yang lebar */
    .container-fluid {
        max-width: 100%;
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin-bottom: 1rem;
    }
    
    #tabelJurnal {
        width: 100%;
        font-size: 0.7rem;
    }
    
    #tabelJurnal th,
    #tabelJurnal td {
        padding: 0.3rem 0.2rem;
        font-size: 0.7rem;
    }
    
    /* Kolom Item bisa wrap */
    #tabelJurnal th:nth-child(3),
    #tabelJurnal td:nth-child(3) {
        white-space: normal;
        max-width: 200px;
    }

    /* Pagination styling */
    .pagination {
        margin: 0;
        gap: 0.25rem;
    }
    
    .pagination .page-item {
        margin: 0 2px;
    }
    
    .pagination .page-link {
        color: #6c757d;
        background-color: #fff;
        border: 1px solid #dee2e6;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: all 0.15s ease-in-out;
    }
    
    .pagination .page-link:hover {
        color: #0056b3;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
        z-index: 3;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }
    
    .pagination .page-link:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        outline: none;
    }
</style>

<div class="container-fluid mt-2">
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

    <!-- TOMBOL AKSI -->
    <div class="mb-4 d-flex flex-wrap gap-2">
        <a href="{{ route('jurnal.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Jurnal
        </a>
        <button class="btn btn-success" onclick="exportToExcel()">
            <i class="fas fa-file-excel"></i> Export Excel
        </button>
        <button class="btn btn-danger" onclick="cetakPDF()">
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
            <button type="submit" class="btn btn-info">
                <i class="fas fa-search"></i> Filter
            </button>
            <a href="{{ route('jurnal.index') }}" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Reset
            </a>
        </div>
    </form>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0 text-center">DAFTAR JURNAL</h5>
        </div>
        <div class="card-body p-0">
            @if ($journals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-small mb-0" id="tabelJurnal">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Tanggal</th>
                                <th>Item</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Satuan</th>
                                <th class="table-currency">Harga</th>
                                <th class="table-currency">Total</th>
                                <th class="table-currency">PPN 11%</th>
                                <th>Project</th>
                                <th>Perusahaan</th>
                                <th class="text-center">Ket</th>
                                <th>Nota</th>
                                <th class="text-center">IN/OUT</th>
                                <th class="text-center">Status</th>
                                <th>Account</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = ($journals->currentPage() - 1) * $journals->perPage() + 1; @endphp
                            @foreach ($journals as $journal)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($journal->transaction_date)->format('d/m/Y') }}</td>
                                    <td>{{ $journal->item }}</td>
                                    <td class="text-center">{{ number_format($journal->quantity, 0) }}</td>
                                    <td class="text-center">{{ $journal->satuan ?: '-' }}</td>
                                    <td class="table-currency">Rp {{ number_format($journal->price, 0, ',', '.') }}</td>
                                    <td class="table-currency">Rp {{ number_format($journal->total, 0, ',', '.') }}</td>
                                    <td class="table-currency">
                                        @if ($journal->tax)
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
                                        @if ($journal->type === 'in')
                                            <span class="badge badge-in">IN</span>
                                        @else
                                            <span class="badge badge-out">OUT</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($journal->payment_status === 'lunas')
                                            <span class="badge bg-success">LUNAS</span>
                                        @else
                                            <span class="badge bg-warning text-dark">TIDAK LUNAS</span>
                                        @endif
                                        @if ($journal->is_hutang)
                                            <br><small class="badge badge-hutang mt-1">HUTANG</small>
                                        @elseif($journal->is_piutang)
                                            <br><small class="badge badge-piutang mt-1">PIUTANG</small>
                                        @endif
                                    </td>
                                    <td><small>{{ $journal->account?->code }} - {{ $journal->account?->name }}</small></td>
                                    <td class="text-center">
                                        <a href="{{ route('jurnal.edit', $journal->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                                data-id="{{ $journal->id }}" 
                                                data-item="{{ $journal->item }}"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $journal->id }}" 
                                              action="{{ route('jurnal.destroy', $journal->id) }}" 
                                              method="POST" 
                                              style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="6" class="text-end fw-bold">TOTAL:</td>
                                <td class="table-currency fw-bold">Rp {{ number_format($journals->sum('total'), 0, ',', '.') }}</td>
                                <td class="table-currency fw-bold">Rp {{ number_format($journals->sum('ppn_amount'), 0, ',', '.') }}</td>
                                <td colspan="8"></td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end fw-bold">GRAND TOTAL (Termasuk PPN):</td>
                                <td class="table-currency fw-bold" colspan="2">Rp {{ number_format($journals->sum('final_total'), 0, ',', '.') }}</td>
                                <td colspan="8"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="text-muted small">
                        Showing {{ $journals->firstItem() ?? 0 }} to {{ $journals->lastItem() ?? 0 }} of {{ $journals->total() }} results
                    </div>
                    <div>
                        {{ $journals->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @else
                <div class="alert alert-info m-3">
                    <i class="fas fa-info-circle"></i> Belum ada data jurnal. <a href="{{ route('jurnal.create') }}">Tambah jurnal baru</a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function cetakPDF() {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF({
            orientation: "landscape",
            unit: "mm",
            format: "a4" // A4 landscape: 297mm x 210mm
        });

        const pageWidth = doc.internal.pageSize.getWidth();
        const pageHeight = doc.internal.pageSize.getHeight();
        const margin = 15;

        // Add company logo
        const logo = new Image();
        logo.src = '/images/logo_wb.png';
        
        logo.onload = function() {
            // Draw logo (top left)
            doc.addImage(logo, 'PNG', margin, 5, 25, 25);

            // Company header
            doc.setFontSize(16);
            doc.setFont(undefined, 'bold');
            doc.text("PT MITRA FAJAR KENCANA", pageWidth / 2, 15, { align: "center" });
            
            doc.setFontSize(10);
            doc.setFont(undefined, 'normal');
            doc.text("Jalan Dahlia No.30, Kel. Suka Jadi, Pekanbaru, Riau", pageWidth / 2, 21, { align: "center" });
            doc.text("Telp: Dedi Setiadi: (+62)82285993694 | Email: ptmitrafajarkencana@gmail.com", pageWidth / 2, 26, { align: "center" });
            
            // Line separator
            doc.setLineWidth(0.5);
            doc.line(margin, 32, pageWidth - margin, 32);
            
            // Report title
            doc.setFontSize(14);
            doc.setFont(undefined, 'bold');
            doc.text("LAPORAN JURNAL UMUM", pageWidth / 2, 40, { align: "center" });
            
            // Date info
            doc.setFontSize(9);
            doc.setFont(undefined, 'normal');
            const today = new Date().toLocaleDateString('id-ID', { 
                day: '2-digit', 
                month: 'long', 
                year: 'numeric' 
            });
            doc.text(`Tanggal Cetak: ${today}`, pageWidth / 2, 46, { align: "center" });

            // Collect table data
            const rows = [];
            const tableBody = document.querySelectorAll('#tabelJurnal tbody tr');
            
            tableBody.forEach((tr) => {
                const cells = tr.querySelectorAll('td');
                if (cells.length > 0) {
                    rows.push([
                        cells[0]?.textContent.trim() || '', // No
                        cells[1]?.textContent.trim() || '', // Tanggal
                        cells[2]?.textContent.trim() || '', // Item
                        cells[3]?.textContent.trim() || '', // Qty
                        cells[4]?.textContent.trim() || '', // Satuan
                        cells[5]?.textContent.trim() || '', // Harga
                        cells[6]?.textContent.trim() || '', // Total
                        cells[7]?.textContent.trim() || '', // PPN
                        cells[8]?.textContent.trim() || '', // Project
                        cells[9]?.textContent.trim() || '', // Perusahaan
                        cells[10]?.textContent.trim() || '', // Ket
                        cells[11]?.textContent.trim() || '', // Nota
                        cells[12]?.textContent.trim() || '', // IN/OUT
                        cells[13]?.textContent.trim().replace(/\s+/g, ' ') || '', // Status
                        cells[14]?.textContent.trim() || '', // Account
                    ]);
                }
            });

            // Add table
            doc.autoTable({
                startY: 52,
                head: [
                    ["No", "Tanggal", "Item", "Qty", "Satuan", "Harga", "Total", "PPN 11%", "Project", 
                     "Perusahaan", "Ket", "Nota", "IN/OUT", "Status", "Account"]
                ],
                body: rows,
                foot: [
                    [
                        { content: 'TOTAL:', colSpan: 6, styles: { halign: 'right', fontStyle: 'bold' } },
                        '{{ $journals->sum("total") > 0 ? "Rp " . number_format($journals->sum("total"), 0, ",", ".") : "-" }}',
                        '{{ $journals->sum("ppn_amount") > 0 ? "Rp " . number_format($journals->sum("ppn_amount"), 0, ",", ".") : "-" }}',
                        { content: '', colSpan: 7 }
                    ],
                    [
                        { content: 'GRAND TOTAL (Termasuk PPN):', colSpan: 6, styles: { halign: 'right', fontStyle: 'bold' } },
                        { content: '{{ $journals->sum("final_total") > 0 ? "Rp " . number_format($journals->sum("final_total"), 0, ",", ".") : "-" }}', 
                          colSpan: 2, styles: { fontStyle: 'bold' } },
                        { content: '', colSpan: 7 }
                    ]
                ],
                theme: 'grid',
                styles: {
                    fontSize: 7,
                    cellPadding: 1.5,
                    overflow: 'linebreak'
                },
                headStyles: {
                    fillColor: [41, 128, 185],
                    textColor: 255,
                    fontStyle: 'bold',
                    halign: 'center'
                },
                footStyles: {
                    fillColor: [240, 240, 240],
                    textColor: 0,
                    fontStyle: 'bold'
                },
                columnStyles: {
                    0: { halign: 'center', cellWidth: 8 },
                    1: { halign: 'center', cellWidth: 18 },
                    2: { cellWidth: 30 },
                    3: { halign: 'center', cellWidth: 10 },
                    4: { halign: 'center', cellWidth: 12 },
                    5: { halign: 'right', cellWidth: 20 },
                    6: { halign: 'right', cellWidth: 20 },
                    7: { halign: 'right', cellWidth: 18 },
                    8: { cellWidth: 20 },
                    9: { cellWidth: 22 },
                    10: { cellWidth: 15 },
                    11: { cellWidth: 18 },
                    12: { halign: 'center', cellWidth: 12 },
                    13: { halign: 'center', cellWidth: 20 },
                    14: { fontSize: 6, cellWidth: 25 }
                },
                margin: { left: margin, right: margin },
                didDrawPage: function (data) {
                    // Footer on each page
                    const footerY = pageHeight - 10;
                    doc.setFontSize(8);
                    doc.setFont(undefined, 'normal');
                    
                    // Left footer - printed by
                    doc.text(`Dicetak oleh: {{ Auth::user()->name ?? 'Admin' }}`, margin, footerY);
                    
                    // Center footer - page number
                    doc.text(
                        `Halaman ${data.pageNumber} dari ${doc.internal.getNumberOfPages()}`, 
                        pageWidth / 2, 
                        footerY, 
                        { align: 'center' }
                    );
                    
                    // Right footer - timestamp
                    const timestamp = new Date().toLocaleString('id-ID');
                    doc.text(timestamp, pageWidth - margin, footerY, { align: 'right' });
                }
            });

            // Save PDF
            doc.save(`Laporan_Jurnal_${today.replace(/\s/g, '_')}.pdf`);
        };
        
        logo.onerror = function() {
            // If logo fails to load, continue without it
            alert('Logo tidak ditemukan. PDF akan dibuat tanpa logo.');
            // You can call the same code without logo here if needed
        };
    }

    function exportToExcel() {
        // Create a new workbook
        const wb = XLSX.utils.book_new();
        
        // Prepare header rows
        const header1 = ["LAPORAN JURNAL UMUM"];
        const header2 = ["PT MITRA FAJAR KENCANA"];
        const header3 = ["PERIODE {{ date('Y') }}"];
        const header4 = ["Tanggal Cetak: " + new Date().toLocaleDateString('id-ID')];
        const header5 = []; // Empty row
        
        // Table headers
        const tableHeaders = [
            "No", "Tanggal", "Item", "Qty", "Satuan", "Harga (@)", "Total", "PPN 11%", 
            "Project", "Perusahaan", "Ket", "Nota", "IN/OUT", "Status", "Account", "Aksi"
        ];
        
        // Collect data from table
        const dataRows = [];
        let no = 1;
        document.querySelectorAll('#tabelJurnal tbody tr').forEach((tr) => {
            const cells = tr.querySelectorAll('td');
            if (cells.length > 0) {
                dataRows.push([
                    no++,
                    cells[1]?.textContent.trim() || '',
                    cells[2]?.textContent.trim() || '',
                    cells[3]?.textContent.trim() || '',
                    cells[4]?.textContent.trim() || '',
                    cells[5]?.textContent.trim() || '',
                    cells[6]?.textContent.trim() || '',
                    cells[7]?.textContent.trim() || '',
                    cells[8]?.textContent.trim() || '',
                    cells[9]?.textContent.trim() || '',
                    cells[10]?.textContent.trim() || '',
                    cells[11]?.textContent.trim() || '',
                    cells[12]?.textContent.trim() || '',
                    cells[13]?.textContent.trim().replace(/\s+/g, ' ') || '',
                    cells[14]?.textContent.trim() || '',
                    'Edit/Delete'
                ]);
            }
        });
        
        // Footer rows
        const totalRow = [
            "", "", "", "", "", "TOTAL:", 
            "{{ number_format($journals->sum('total'), 0, ',', '.') }}", 
            "{{ number_format($journals->sum('ppn_amount'), 0, ',', '.') }}", 
            "", "", "", "", "", "", "", ""
        ];
        
        const grandTotalRow = [
            "", "", "", "", "", "GRAND TOTAL (Termasuk PPN):", 
            "{{ number_format($journals->sum('final_total'), 0, ',', '.') }}", 
            "", "", "", "", "", "", "", "", ""
        ];
        
        // Combine all data
        const wsData = [
            header1,
            header2,
            header3,
            header4,
            header5,
            tableHeaders,
            ...dataRows,
            totalRow,
            grandTotalRow
        ];
        
        // Create worksheet
        const ws = XLSX.utils.aoa_to_sheet(wsData);
        
        // Set column widths
        ws['!cols'] = [
            { wch: 5 },  // No
            { wch: 12 }, // Tanggal
            { wch: 25 }, // Item
            { wch: 8 },  // Qty
            { wch: 10 }, // Satuan
            { wch: 15 }, // Harga
            { wch: 15 }, // Total
            { wch: 15 }, // PPN
            { wch: 20 }, // Project
            { wch: 20 }, // Perusahaan
            { wch: 15 }, // Ket
            { wch: 15 }, // Nota
            { wch: 10 }, // IN/OUT
            { wch: 20 }, // Status
            { wch: 25 }, // Account
            { wch: 15 }  // Aksi
        ];
        
        // Merge cells for headers
        ws['!merges'] = [
            { s: { r: 0, c: 0 }, e: { r: 0, c: 15 } }, // Title
            { s: { r: 1, c: 0 }, e: { r: 1, c: 15 } }, // Company
            { s: { r: 2, c: 0 }, e: { r: 2, c: 15 } }, // Period
            { s: { r: 3, c: 0 }, e: { r: 3, c: 15 } }, // Date
        ];
        
        // Add worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, "Jurnal");
        
        // Save file
        XLSX.writeFile(wb, `Laporan_Jurnal_${new Date().toISOString().slice(0,10)}.xlsx`);
    }

    // SweetAlert2 for delete confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const item = this.getAttribute('data-item');
                
                Swal.fire({
                    title: 'Are you sure?',
                    html: `You won't be able to revert this!<br><small class="text-muted">Item: <strong>${item}</strong></small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal-custom-popup',
                        title: 'swal-custom-title',
                        htmlContainer: 'swal-custom-html'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            });
        });
    });
</script>

@endsection
