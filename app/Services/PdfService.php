<?php

namespace App\Services;

use Mpdf\Mpdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

class PdfService
{
    public static function generatePdf(string $view, $data = [], array $config = [])
    {
        $pdf = App::make('dompdf.wrapper');
        
        $html = view($view, ["data" => $data])->render();
        $pdf->loadHTML($html);
        return $pdf;
    }
}
