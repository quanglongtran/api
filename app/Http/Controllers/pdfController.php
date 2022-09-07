<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class pdfController extends Controller
{
    public function index()
    {
        $data = ['name' => 'Quang Long'];
        $pdf = PDF::loadView('pdf.invoice', $data);
        return $pdf->stream('report.pdf',);
    }
}
