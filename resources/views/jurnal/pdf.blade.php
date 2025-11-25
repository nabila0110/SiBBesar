<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Jurnal Umum</title>
    <style>
        @page {
            margin: 6mm 10mm 10mm 8mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 7pt;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }

        .header {
            margin-bottom: 8px;
            padding-bottom: 8px;
            border-bottom: 3px solid #000;
            position: relative;
        }

        .header-content {
            text-align: center;
            padding-left: 80px;
        }

        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 70px;
            height: 70px;
        }

        .company-name {
            font-size: 16pt;
            font-weight: bold;
            margin: 0 0 3px 0;
            letter-spacing: 0.5px;
        }

        .company-address {
            font-size: 8.5pt;
            margin: 2px 0;
            font-weight: normal;
        }

        .report-title {
            text-align: center;
            margin: 8px 0 2px 0;
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .report-date {
            text-align: center;
            font-size: 8pt;
            margin: 0 0 8px 0;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 6.5pt;
        }

        th {
            background-color: #4472C4;
            color: white;
            padding: 4px 2px;
            text-align: center;
            border: 0.5px solid #000;
            font-weight: bold;
            font-size: 6.5pt;
            line-height: 1.1;
        }

        td {
            padding: 3px 2px;
            border: 0.5px solid #999;
            font-size: 6.5pt;
            line-height: 1.1;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            position: fixed;
            bottom: 1mm;
            left: 10mm;
            right: 10mm;
            font-size: 7pt;
            display: table;
            width: calc(100% - 20mm);
            border-top: 0.5px solid #000;
            padding-top: 3px;
        }

        .footer-left {
            display: table-cell;
            text-align: left;
            width: 33%;
            vertical-align: middle;
        }

        .footer-center {
            display: table-cell;
            text-align: center;
            width: 34%;
            vertical-align: middle;
        }

        .footer-right {
            display: table-cell;
            text-align: right;
            width: 33%;
            vertical-align: middle;
        }

        .total-row {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 7pt;
        }

        .grand-total-row {
            background-color: #e0e0e0;
            font-weight: bold;
            font-size: 7pt;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('images/logo_wb.png') }}" class="logo" alt="Logo">
        <div class="header-content">
            <div class="company-name">PT MITRA FAJAR KENCANA</div>
            <div class="company-address">Jalan Dahlia No.30, Kel. Suka Jadi, Pekanbaru, Riau</div>
            <div class="company-address">Telp: Dedi Setiadi: (+62)82285993694 | Email: ptmitrafajarkencana@gmail.com
            </div>
        </div>
    </div>

    <div class="report-title">LAPORAN JURNAL UMUM</div>
    <div class="report-date">Tanggal Cetak: {{ $tanggal_cetak }}</div>

    <table>
        <thead>
            <tr>
                <th style="width: 2.5%;">No</th>
                <th style="width: 6%;">Tanggal</th>
                <th style="width: 13%;">Item</th>
                <th style="width: 3.5%;">Qty</th>
                <th style="width: 5%;">Satuan</th>
                <th style="width: 8%;">Harga</th>
                <th style="width: 8%;">Total</th>
                <th style="width: 7%;">PPN 11%</th>
                <th style="width: 9%;">Project</th>
                <th style="width: 9%;">Perusahaan</th>
                <th style="width: 5%;">Ket</th>
                <th style="width: 6%;">Nota</th>
                <th style="width: 5%;">IN/OUT</th>
                <th style="width: 6%;">Status</th>
                <th style="width: 12%;">Account</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($journals as $index => $journal)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($journal->transaction_date)) }}</td>
                    <td>{{ $journal->item }}</td>
                    <td class="text-center">{{ $journal->qty }}</td>
                    <td class="text-center">{{ $journal->satuan }}</td>
                    <td class="text-right">Rp {{ number_format($journal->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($journal->total, 0, ',', '.') }}</td>
                    <td class="text-right">
                        {{ $journal->ppn_amount > 0 ? 'Rp ' . number_format($journal->ppn_amount, 0, ',', '.') : '-' }}
                    </td>
                    <td>{{ $journal->project }}</td>
                    <td>{{ $journal->company }}</td>
                    <td class="text-center">{{ $journal->notes }}</td>
                    <td>{{ $journal->nota }}</td>
                    <td class="text-center">{{ strtoupper($journal->type) }}</td>
                    <td class="text-center">{{ strtoupper($journal->payment_status) }}</td>
                    <td style="font-size: 6pt;">
                        {{ $journal->account ? $journal->account->code . ' - ' . $journal->account->name : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="text-right"><strong>TOTAL:</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($journals->sum('total'), 0, ',', '.') }}</strong>
                </td>
                <td class="text-right"><strong>Rp
                        {{ number_format($journals->sum('ppn_amount'), 0, ',', '.') }}</strong></td>
                <td colspan="7"></td>
            </tr>
            <tr class="grand-total-row">
                <td colspan="6" class="text-right"><strong>GRAND TOTAL (Termasuk PPN):</strong></td>
                <td colspan="2" class="text-right"><strong>Rp
                        {{ number_format($journals->sum('final_total'), 0, ',', '.') }}</strong></td>
                <td colspan="7"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="footer-left">Dicetak oleh: {{ $user }}</div>
        <div class="footer-center"></div>
        <div class="footer-right">{{ $tanggal_cetak }}</div>
    </div>

    <script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->get_font("Arial", "normal");
        $size = 7;
        $color = array(0, 0, 0);

        $user = "{{ $user }}";
        $date = "{{ $tanggal_cetak }}";
        $pageText = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";

        // Lebar halaman (berguna untuk menghitung posisi tengah)
        $width = $pdf->get_width();

        // Posisi horizontal (X)
        $centerX = ($width / 2) - ($fontMetrics->get_text_width($pageText, $font, $size) / 2);
        $leftX = 10; 
        $rightX = $width - ($fontMetrics->get_text_width($date, $font, $size) + 10);

        // Posisi vertical (Y)
        $y = $pdf->get_height() - 35;  // aman tidak bertabrakan dengan margin

        // Cetak masing-masing text
        $pdf->page_text($leftX, $y, "Dicetak oleh: " . $user, $font, $size, $color);
        $pdf->page_text($centerX, $y, $pageText, $font, $size, $color);
        $pdf->page_text($rightX, $y, $date, $font, $size, $color);
    }
</script>
</body>

</html>
