<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TransactionExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Transaction::query()
            ->with(['item.category', 'item.unit', 'supplier']);

        if (!empty($this->filters['date_from'])) {
            $query->whereDate('transaction_date', '>=', $this->filters['date_from']);
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate('transaction_date', '<=', $this->filters['date_to']);
        }

        if (!empty($this->filters['type'])) {
            $query->where('type', $this->filters['type']);
        }

        return $query->orderBy('transaction_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jenis',
            'Nama Barang',
            'Kategori',
            'Satuan',
            'Supplier',
            'Jumlah',
            'Harga Satuan',
            'Total Harga',
            'Keterangan',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->transaction_date->format('d/m/Y'),
            $transaction->type === 'in' ? 'Masuk' : 'Keluar',
            $transaction->item->name ?? '-',
            $transaction->item->category->name ?? '-',
            $transaction->item->unit->symbol ?? '-',
            $transaction->supplier->name ?? '-',
            $transaction->quantity,
            'Rp ' . number_format($transaction->unit_price, 0, ',', '.'),
            'Rp ' . number_format($transaction->total_price, 0, ',', '.'),
            $transaction->notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE2E8F0'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}