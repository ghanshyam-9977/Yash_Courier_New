<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BranchPaymentExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $payments;
    protected $hubs;

    public function __construct($payments, $hubs)
    {
        $this->payments = $payments;
        $this->hubs = $hubs;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Branch to Branch',
            'Transport Type',
            'Description',
            'Quantity',
            'Amount',
            'Request Date'
        ];
    }

    public function collection()
    {
        return $this->payments->map(function ($payment, $index) {
            $fromBranch = $payment->from_branch_id;
            $toBranch = $payment->to_branch_id;
            
            // Get branch names if available
            if (isset($this->hubs[$payment->from_branch_id]) && isset($this->hubs[$payment->from_branch_id]->name)) {
                $fromBranch = $this->hubs[$payment->from_branch_id]->name;
            }
            
            if (isset($this->hubs[$payment->to_branch_id]) && isset($this->hubs[$payment->to_branch_id]->name)) {
                $toBranch = $this->hubs[$payment->to_branch_id]->name;
            }
            
            return [
                'id' => $index + 1,
                'branch_to_branch' => $fromBranch . ' → ' . $toBranch,
                'transport_type' => $payment->transport_type ?? '',
                'description' => $payment->description ?? '',
                'quantity' => $payment->quantity ?? 0,
                'amount' => $payment->amount ?? 0,
                'request_date' => \Carbon\Carbon::parse($payment->created_at)->format('d M Y')
            ];
        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $total_rows = $event->sheet->getHighestRow();
                $last_row = $total_rows + 1;
                
                // Adding totals row
                $event->sheet->setCellValue('D'.$last_row, 'Total=');
                $event->sheet->setCellValue('E'.$last_row, '=SUM(E2:E'.$total_rows.')'); // total quantity
                $event->sheet->setCellValue('F'.$last_row, '=SUM(F2:F'.$total_rows.')'); // total amount
                
                $event->sheet->getStyle($last_row)->applyFromArray([
                    'font' => ['bold' => true]
                ]);
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
