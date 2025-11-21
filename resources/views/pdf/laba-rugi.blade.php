<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>
    <style>
        @page {
            margin: 120px 50px 80px 50px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }
        .header-kop {
            position: fixed;
            top: -100px;
            left: 0;
            right: 0;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            display: table;
            width: 100%;
        }
        .logo-container {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            padding-right: 15px;
        }
        .logo-container img {
            width: 70px;
            height: auto;
        }
        .company-info {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .header-kop h1 {
            margin: 0;
            font-size: 16pt;
            font-weight: bold;
            color: #000;
        }
        .header-kop p {
            margin: 2px 0;
            font-size: 9pt;
        }
        .footer {
            position: fixed;
            bottom: -60px;
            left: 0;
            right: 0;
            border-top: 2px solid #000;
            padding-top: 5px;
            font-size: 8pt;
            text-align: center;
        }
        .page-number:after {
            content: counter(page);
        }
        .header {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14pt;
            font-weight: bold;
        }
        .periode {
            text-align: center;
            font-size: 9pt;
            margin-bottom: 15px;
            font-style: italic;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 9pt;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .category-header {
            background-color: #e0e0e0;
            font-weight: bold;
        }
        .text-end {
            text-align: right;
        }
        .fw-bold {
            font-weight: bold;
        }
        .ps-4 {
            padding-left: 20px;
        }
        .bg-success {
            background-color: #d1e7dd;
        }
        .bg-danger {
            background-color: #f8d7da;
        }
        .bg-primary {
            background-color: #cfe2ff;
            font-weight: bold;
        }
        .spacer {
            height: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <!-- HEADER KOP -->
    <div class="header-kop">
        <div class="logo-container">
            <img src="{{ public_path('images/logo_wb.png') }}" alt="Logo">
        </div>
        <div class="company-info">
            <h1>MITRA FAJAR KENCANA</h1>
            <p>Jl. Contoh Alamat No. 123, Jakarta Selatan 12345</p>
            <p>Telp: (021) 1234-5678 | Email: info@mitrafajarkencana.com</p>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i') }} | Halaman <span class="page-number"></span></p>
        <p style="font-size: 7pt; margin-top: 3px;">Dokumen ini dicetak oleh sistem SiBBesar - Sistem Informasi Akuntansi</p>
    </div>

    <div class="header">
        <h2>LAPORAN LABA RUGI (INCOME STATEMENT)</h2>
    </div>
    <div class="periode">
        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}
    </div>

    @php
        $revenueCategories = collect($labaRugiData)->filter(fn($item) => $item['category']->type === 'revenue');
        $expenseCategories = collect($labaRugiData)->filter(fn($item) => $item['category']->type === 'expense');
        
        $totalRevenue = $revenueCategories->sum('total');
        $totalExpense = $expenseCategories->sum('total');
        $netIncome = $totalRevenue - $totalExpense;
    @endphp

    <table>
        <thead>
            <tr>
                <th width="15%">Kode Akun</th>
                <th width="45%">Nama Akun</th>
                <th width="20%">Jumlah</th>
                <th width="20%">Total</th>
            </tr>
        </thead>
        <tbody>
            <!-- PENDAPATAN -->
            @foreach($revenueCategories as $categoryData)
                <tr class="category-header bg-success">
                    <td colspan="4">{{ $categoryData['category']->code }}-{{ $categoryData['category']->name }}</td>
                </tr>
                
                @foreach($categoryData['accounts'] as $accountData)
                    <tr>
                        <td>{{ $accountData['account']->category->code }}-{{ $accountData['account']->code }}</td>
                        <td class="ps-4">{{ $accountData['account']->name }}</td>
                        <td class="text-end">{{ number_format($accountData['balance'], 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                @endforeach
                
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">TOTAL {{ strtoupper($categoryData['category']->name) }}:</td>
                    <td class="text-end">{{ number_format($categoryData['total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr class="fw-bold bg-success">
                <td colspan="3" class="text-end">TOTAL PENDAPATAN:</td>
                <td class="text-end">{{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>

            <tr class="spacer"><td colspan="4"></td></tr>

            <!-- BEBAN -->
            @foreach($expenseCategories as $categoryData)
                <tr class="category-header bg-danger">
                    <td colspan="4">{{ $categoryData['category']->code }}-{{ $categoryData['category']->name }}</td>
                </tr>
                
                @foreach($categoryData['accounts'] as $accountData)
                    <tr>
                        <td>{{ $accountData['account']->category->code }}-{{ $accountData['account']->code }}</td>
                        <td class="ps-4">{{ $accountData['account']->name }}</td>
                        <td class="text-end">{{ number_format($accountData['balance'], 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                @endforeach
                
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">TOTAL {{ strtoupper($categoryData['category']->name) }}:</td>
                    <td class="text-end">{{ number_format($categoryData['total'], 0, ',', '.') }}</td>
                </tr>
            @endforeach

            <tr class="fw-bold bg-danger">
                <td colspan="3" class="text-end">TOTAL BEBAN:</td>
                <td class="text-end">{{ number_format($totalExpense, 0, ',', '.') }}</td>
            </tr>

            <tr class="spacer"><td colspan="4"></td></tr>

            <!-- LABA/RUGI BERSIH -->
            <tr class="fw-bold bg-primary">
                <td colspan="3" class="text-end">
                    @if($netIncome >= 0)
                        LABA BERSIH:
                    @else
                        RUGI BERSIH:
                    @endif
                </td>
                <td class="text-end">{{ number_format(abs($netIncome), 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
