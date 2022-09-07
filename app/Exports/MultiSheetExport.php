<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetExport implements WithMultipleSheets
{
    use Exportable;
    
    private $year;
    
    public function __construct(int $year)
    {
        $this->year = $year;
    }
    
    public function sheets(): array
    {
        $sheets = [];

        foreach (range(1, 12) as $month) {
            $sheets[] = new ExportFile($this->year, $month);
        }

        return $sheets;
    }
}
