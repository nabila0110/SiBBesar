<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JurnalExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
{
    protected $journals;

    public function __construct($journals)
    {
        $this->journals = $journals;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->journals->map(function ($journal, $index) {
            return [
                'no' => $index + 1,
                'tanggal' => date('d/m/Y', strtotime($journal->transaction_date)),
                'item' => $journal->item,
                'qty' => $journal->qty,
                'satuan' => $journal->satuan,
                'harga' => 'Rp ' . number_format($journal->price, 0, ',', '.'),
                'total' => 'Rp ' . number_format($journal->total, 0, ',', '.'),
                'ppn' => $journal->ppn_amount > 0 ? 'Rp ' . number_format($journal->ppn_amount, 0, ',', '.') : '-',
                'project' => $journal->project,
                'perusahaan' => $journal->company,
                'ket' => $journal->notes,
                'nota' => $journal->nota,
                'type' => strtoupper($journal->type),
                'status' => ucfirst($journal->payment_status),
                'account' => $journal->account ? $journal->account->code . ' - ' . $journal->account->name : '-',
            ];
        });
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Item',
            'Qty',
            'Satuan',
            'Harga (@)',
            'Total',
            'PPN 11%',
            'Project',
            'Perusahaan',
            'Ket',
            'Nota',
            'IN/OUT',
            'Status',
            'Account',
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 12,
            'C' => 30,
            'D' => 8,
            'E' => 10,
            'F' => 18,
            'G' => 18,
            'H' => 18,
            'I' => 20,
            'J' => 20,
            'K' => 15,
            'L' => 15,
            'M' => 10,
            'N' => 12,
            'O' => 30,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Laporan Jurnal';
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->journals->count() + 1;

        // Style for header
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Style for all data cells
        $sheet->getStyle('A1:O' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Center align for specific columns
        $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B2:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D2:D' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E2:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('M2:M' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('N2:N' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}
