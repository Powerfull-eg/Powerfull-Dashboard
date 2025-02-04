<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class ShopExportExcel implements FromArray, WithEvents, ShouldAutoSize
{
    public $data;
    public $summary;
    public $startDate;
    public $endDate;

    public function __construct($data,$startDate,$endDate){
        $this->data = $data;
        $this->summary = $data->summary ?? [];
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function array(): array
    {
        // Prepare summary rows
        $summaryRows = [];
        foreach ($this->summary as $key => $value) {
            $summaryRows[] = [ucfirst(implode(" ",preg_split('/(?=[A-Z])/', $key, -1, PREG_SPLIT_NO_EMPTY))), $value ?? 0];
        }
        
        // Prepare user rows with headers
        $shopHeaders = ['ID','Name','Phone','Powerfull ID','Provider ID','SIM','Device ID','Address'];
        $shop = $this->data->first();
        $shopRows = [[
            // prepare shop logo             
            $shop->id,
            $shop->name, 
            $shop->phone,
            $shop->device->powerfull_id,
            $shop->provider_id,
            $shop->device->sim_number,
            $shop->device->device_id,
            $shop->address,
        ]];

        $operationsHeaders = ['#','Borrow Time','Return Time','Renting Time','Operation Time'];
        $operationsRows = [];

        foreach ($this->data->operations as $index => $operation) {
            $operationsRows[] = [
                $index + 1,
                $operation->borrowTime,
                $operation->returnTime,
                $operation->returnTime ? secondsToTimeString(\Carbon\Carbon::parse($operation->returnTime)->getTimestamp() - \Carbon\Carbon::parse($operation->borrowTime)->getTimestamp()) : '-',
                $operation->created_at->format('D, M j, Y'),
            ];
        }

        return array_merge($summaryRows, [],[],[$shopHeaders], $shopRows, [],[],[$operationsHeaders], $operationsRows);
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

                // Set bold styling for shop data headers
                $sheet->getStyle('A'.($summarySize + 1).':I'.($summarySize + 1))->getFont()->setBold(true);
                // Set bold styling for the operations headers
                $sheet->getStyle('A'.($summarySize + 3).':I'.($summarySize + 3))->getFont()->setBold(true);
                
                // Auto-size the columns
                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}
