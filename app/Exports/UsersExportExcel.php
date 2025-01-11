<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class UsersExportExcel implements FromArray, WithEvents, ShouldAutoSize
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
            $summaryRows[] = [ucfirst(implode(" ",preg_split('/(?=[A-Z])/', $key, -1, PREG_SPLIT_NO_EMPTY))), $value];
        }

        // Prepare user rows with headers
        $headers = ['ID','Name','Phone','Registered At','Last Operation','Deleted At','Email'];
        $userRows = $this->data->map(function ($user) {
            return [
            $user->id,
            $user->first_name . " " . $user->last_name, 
            0 . $user->phone,
            $user->created_at->format('Y-m-d H:i:s'),
            $user->operations->count() ? $user->operations->last()->created_at->format('Y-m-d H:i:s') : '',
            $user->deleted_at ? $user->deleted_at->format('Y-m-d H:i:s') : '',
            $user->email,
            ];
        })->toArray();

        return array_merge($summaryRows, [[],[]],[$headers], $userRows);
           
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
