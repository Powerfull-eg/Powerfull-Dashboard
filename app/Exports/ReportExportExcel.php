<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class ReportExportExcel implements FromArray, WithEvents, ShouldAutoSize
{
    public $data;
    public $summary;

    public function __construct($data){
        $this->data = $data;
        $this->summary = $data->summary ?? [];
    }

    public function array(): array
    {
        // Prepare summary rows
        $summaryRows = [];
        foreach ($this->summary as $key => $value) {
            $summaryRows[] = [ucfirst(implode(" ",preg_split('/(?=[A-Z])/', $key, -1, PREG_SPLIT_NO_EMPTY))), $value ?? 0];
        }
        
        // Prepare user rows with headers
        $headers = [
            'Powerfull ID',
            'Device ID',
            'Shop Name',
            'SIM Number',
            'Total Operations Orders',
            'Total Operating Hours',
            'Total Amount',
            'Partnership Share',
            'Total Partner Share',
        ];
        
        $dataRows = $this->data->map(function ($device) {
            // prepare shop logo             
            return [
                $device->powerfull_id,
                $device->device_id,
                $device->shop->name,
                $device->sim_number,
                $device->operations->count(),
                intval($device->operations->sum(fn($operation) => $operation->returnTime && $operation->borrowTime ? floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60) : 0)),
                $device->operations->sum('amount'),
                "0 %",
                "0 EGP",
            ];
        })->toArray();

        return array_merge($summaryRows, [[],[]],[$headers], $dataRows);
           
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
                $sheet->getStyle('A'.($summarySize + 1).':I'.($summarySize + 1))->getFont()->setBold(true);

                // Auto-size the columns
                foreach (range('A', $sheet->getHighestColumn()) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            },
        ];
    }
}