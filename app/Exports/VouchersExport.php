<?php

namespace App\Exports;

use App\Models\Voucher;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class VouchersExport implements FromArray, WithEvents, ShouldAutoSize
{
    public $data;
    public $summary;

    public function __construct($data){
        $this->data = $data;
        $this->summary = $data->summary ?? [];
    }

    public function array(): array 
    {
        // dd($this->data);
        // Prepare summary rows
        $summaryRows = [];
        foreach ($this->summary as $key => $value) {
            $summaryRows[] = [ucfirst(implode(" ",preg_split('/(?=[A-Z])/', $key, -1, PREG_SPLIT_NO_EMPTY))), $value];
        }
        
        $headers = ['#','Code','Value','Min Amount','Max Discount','Start Date','Expiration Date'];

        $counter = 0;
        $bodyRows = $this->data->vouchers->map(function ($voucher) use (&$counter) {
            return [
                ++$counter,
                $voucher->code,
                $voucher->value . ' ' . ($voucher->type == 0 ? ' %' : ' EGP'),
                $voucher->min_amount . ' EGP',
                $voucher->max_discount . ' EGP',
                $voucher->from,
                $voucher->to
            ];
        })->toArray();

        return array_merge($summaryRows, [[],[]],[$headers], $bodyRows);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $summarySize = count($this->summary);

                // Set background color and text color for the summary rows
                $sheet->getStyle('A1:B' . ($summarySize))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('EA711B');
                $sheet->getStyle('A1:B' . ($summarySize))->getFont()->getColor()->setRGB('FFFFFF');
                
                // Set bold styling for the summary rows
                $sheet->getStyle('A1:B' . ($summarySize))->getFont()->setBold(true);

                // Set bold styling for the headers
                $sheet->getStyle('A'.($summarySize + 1).':G'.($summarySize + 1))->getFont()->setBold(true);

                // Auto-size the columns
                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
