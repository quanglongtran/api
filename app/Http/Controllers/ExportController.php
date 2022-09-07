<?php

namespace App\Http\Controllers;

use App\Exports\ExportFile;
use App\Exports\MultiSheetExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function pdf()
    {
        $data = ['name' => 'Quang Long'];
        $pdf = PDF::loadView('pdf.invoice', $data);
        return $pdf->stream('report.pdf',);
    }

    public function sheet(Excel $excel)
    {
        // return Excel::download(new ExportFile(), 'users.html', \Maatwebsite\Excel\Excel::HTML);
        // return (new ExportFile)->download('users.xlsx', \Maatwebsite\Excel\Excel::XLSX);
        // $excel->store()
    }

    public function multiSheet()
    {
        return (new MultiSheetExport(2022))->download('users.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
