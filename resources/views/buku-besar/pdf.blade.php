<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Buku Besar</title>
    <style>
        @page {
            margin: 8mm 10mm 20mm 10mm;
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
        .account-header {
            background-color: #4472C4;
            color: white;
            padding: 4px 6px;
            margin-top: 12px;
            margin-bottom: 4px;
            font-weight: bold;
            font-size: 7pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
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
        .subtotal-row {
            background-color: #D9E2F3;
            font-weight: bold;
            font-size: 6.5pt;
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
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo_wb.png') }}" class="logo" alt="Logo">
        <div class="header-content">
            <div class="company-name">PT MITRA FAJAR KENCANA</div>
            <div class="company-address">Jalan Dahlia No.30, Kel. Suka Jadi, Pekanbaru, Riau</div>
            <div class="company-address">Telp: Dedi Setiadi: (+62)82285993694 | Email: ptmitrafajarkencana@gmail.com</div>
        </div>
    </div>

    <div class="report-title">LAPORAN BUKU BESAR</div>
    <div class="report-date">Tanggal Cetak: {{ $tanggal_cetak }}</div>

    @php $no = 1; @endphp
    @foreach($groupedJournals as $accountId => $journals)
        @php $account = $journals->first()->account; @endphp
        
        <div class="account-header">
            ACCOUNT: {{ $account->code }} - {{ $account->name }}
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 3%;">No</th>
                    <th style="width: 6%;">Tanggal</th>
                    <th style="width: 6%;">Nota</th>
                    <th style="width: 16%;">Item</th>
                    <th style="width: 4%;">Qty</th>
                    <th style="width: 5%;">Satuan</th>
                    <th style="width: 8%;">Harga</th>
                    <th style="width: 9%;">Total</th>
                    <th style="width: 8%;">PPN 11%</th>
                    <th style="width: 9%;">Project</th>
                    <th style="width: 7%;">Ket</th>
                    <th style="width: 5%;">Type</th>
                    <th style="width: 7%;">Status</th>
                    <th style="width: 7%;">Klasifikasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($journals as $journal)
                @php
                    $classification = $journal->account ? ($journal->account->category->code . '-' . $journal->account->code . ' - ' . $journal->account->name) : '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($journal->transaction_date)) }}</td>
                    <td>{{ $journal->nota }}</td>
                    <td>{{ $journal->item }}</td>
                    <td class="text-center">{{ number_format($journal->quantity, 0) }}</td>
                    <td class="text-center">{{ $journal->satuan ?: '-' }}</td>
                    <td class="text-right">Rp {{ number_format($journal->price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($journal->total, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $journal->tax ? 'Rp ' . number_format($journal->ppn_amount, 0, ',', '.') : '-' }}</td>
                    <td>{{ $journal->project ?: '-' }}</td>
                    <td class="text-center">{{ $journal->ket ?: '-' }}</td>
                    <td class="text-center">{{ strtoupper($journal->type) }}</td>
                    <td class="text-center">{{ strtoupper($journal->payment_status) }}</td>
                    <td style="font-size: 5.5pt;">{{ $classification }}</td>
                </tr>
                @endforeach
                <tr class="subtotal-row">
                    <td colspan="7" class="text-right">SUBTOTAL:</td>
                    <td class="text-right">Rp {{ number_format($journals->sum('total'), 0, ',', '.') }}</td>
                    <td colspan="6"></td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <div class="footer">
        <div class="footer-left">Dicetak oleh: {{ $user }}</div>
        <div class="footer-center"></div>
        <div class="footer-right">{{ $tanggal_cetak }}</div>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $font = null;
            $size = 7;
            $color = array(0,0,0);
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $pdf->page_text(385, 575, $text, $font, $size, $color);
        }
    </script>
</body>
</html>
