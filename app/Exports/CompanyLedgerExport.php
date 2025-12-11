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

class CompanyLedgerExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
{
    protected $groupedJournals;
    protected $company;

    public function __construct($groupedJournals, $company)
    {
        $this->groupedJournals = $groupedJournals;
        $this->company = $company;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = collect();
        $no = 1;

        foreach ($this->groupedJournals as $accountId => $journals) {
            $account = $journals->first()->account;
            
            // Add account header row
            $data->push([
                'no' => '',
                'tanggal' => 'ACCOUNT: ' . $account->code . ' - ' . $account->name,
                'item' => '',
                'qty' => '',
                'satuan' => '',
                'harga' => '',
                'total' => '',
                'ppn' => '',
                'project' => '',
                'ket' => '',
                'nota' => '',
                'type' => '',
                'status' => '',
            ]);

            // Add journal entries for this account
            foreach ($journals as $journal) {
                $data->push([
                    'no' => $no++,
                    'tanggal' => date('d/m/Y', strtotime($journal->transaction_date)),
                    'item' => $journal->item,
                    'qty' => $journal->quantity,
                    'satuan' => $journal->satuan,
                    'harga' => 'Rp ' . number_format($journal->price, 0, ',', '.'),
                    'total' => 'Rp ' . number_format($journal->total, 0, ',', '.'),
                    'ppn' => $journal->ppn_amount > 0 ? 'Rp ' . number_format($journal->ppn_amount, 0, ',', '.') : '-',
                    'project' => $journal->project,
                    'ket' => $journal->ket,
                    'nota' => $journal->nota,
                    'type' => strtoupper($journal->type),
                    'status' => ucfirst($journal->payment_status),
                ]);
            }

            // Add subtotal row
            $subtotal = $journals->sum('total');
            $data->push([
                'no' => '',
                'tanggal' => '',
                'item' => '',
                'qty' => '',
                'satuan' => '',
                'harga' => 'SUBTOTAL:',
                'total' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                'ppn' => '',
                'project' => '',
                'ket' => '',
                'nota' => '',
                'type' => '',
                'status' => '',
            ]);

            // Add empty row
            $data->push([
                'no' => '',
                'tanggal' => '',
                'item' => '',
                'qty' => '',
                'satuan' => '',
                'harga' => '',
                'total' => '',
                'ppn' => '',
                'project' => '',
                'ket' => '',
                'nota' => '',
                'type' => '',
                'status' => '',
            ]);
        }

        return $data;
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
            'Ket',
            'Nota',
            'IN/OUT',
            'Status',
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
            'M' => 12,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Buku Besar ' . $this->company->code;
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // Style for header
        $sheet->getStyle('A1:M1')->applyFromArray([
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

        return [];
    }
}
