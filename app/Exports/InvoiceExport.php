<?php

namespace App\Exports;

use App\Http\Resources\InvoiceParcelResource;
use App\Models\Backend\Merchantpanel\Invoice;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Reader;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoiceExport implements FromCollection,WithHeadings,WithStyles,WithEvents
{
    
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $invoiceParcels;
    public function __construct($invoiceParcels)
    {
        $this->invoiceParcels = $invoiceParcels; 
    }
    public function headings(): array
    {
        return [
            'Delivery Date',
            'Customer Name',
            'Customer Phone',
            'Customer Address',
            'Invoice ID',
            'Tracking ID',
            'Status',
            'Invoice Status',
            'Cash Collection',
            'Delivery Charge',
            'Return Charge',
            'Vat',
            'COD',
            'Total Charge',
            'Paid out'
        ];
    }

 

    public function collection()
    {   
        return InvoiceParcelResource::collection($this->invoiceParcels);  
    }

     
    public function registerEvents(): array
    {
        
        return [ 
            // Array callable, refering to a static method.
            AfterSheet::class =>function(AfterSheet $event)  { 
                $total_rows = $event->sheet->getHighestRow(); 
                $last_row = $total_rows+1; 
                $event->sheet->setCellValue('H'.$last_row,'Total=');//total cash collection 
                $event->sheet->setCellValue('I'.$last_row,'=SUM(I2:I'.$total_rows.')');//total cash collection
                $event->sheet->setCellValue('J'.$last_row,'=SUM(J2:J'.$total_rows.')');//total delivery charges
                $event->sheet->setCellValue('K'.$last_row,'=SUM(K2:K'.$total_rows.')');//total return charges
                $event->sheet->setCellValue('L'.$last_row,'=SUM(L2:L'.$total_rows.')');//total vat
                $event->sheet->setCellValue('M'.$last_row,'=SUM(M2:M'.$total_rows.')');//total cod
                $event->sheet->setCellValue('N'.$last_row,'=SUM(N2:N'.$total_rows.')');//total total charges
                $event->sheet->setCellValue('O'.$last_row,'=SUM(O2:O'.$total_rows.')');//total piad out 
 
                $event->sheet->getStyle($last_row)->applyFromArray([
                        'font' => ['bold' => true]
                    ]);
 
            },            
        ];
    }


    public function styles(Worksheet $sheet)
    { 
      
        return [
            // Style the first row as bold text.
            1        => ['font' => ['bold' => true]],  
        ];
    }

}
