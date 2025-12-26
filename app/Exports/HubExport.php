<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class HubExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $hubs;

    public function __construct($hubs)
    {
        $this->hubs = $hubs;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Phone',
            'Address',
            'Status',
            'Created At'
        ];
    }

    public function collection()
    {
        return $this->hubs->map(function ($hub, $index) {
            return [
                'id' => $index + 1,
                'name' => $hub->name ?? '',
                'phone' => $hub->phone ?? '',
                'address' => $hub->address ?? '',
                'status' => $hub->status == \App\Enums\Status::ACTIVE ? 'Active' : 'Inactive',
                'created_at' => \Carbon\Carbon::parse($hub->created_at)->format('d M Y')
            ];
        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $total_rows = $event->sheet->getHighestRow();
                $last_row = $total_rows + 1;
                
                // Adding totals row for balance
                
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
