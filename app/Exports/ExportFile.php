<?php

namespace App\Exports;

use App\Models\User;
use App\Models\UserClass;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ExportFile implements
    FromCollection,
    FromQuery,
    ShouldAutoSize,
    WithHeadings,
    WithMapping,
    WithEvents,
    WithDrawings
{
    use Exportable;

    private int $year;
    private int $month;

    public function __construct(int $year, int $month)
    {
        $this->year = $year;
        $this->month = $month;
    }
    
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::all();
    }

    public function query()
    {
        return UserClass::query()->with('users')
        ->whereYear('created_at', $this->year)
        ->whereMonth('created_at', $this->month);
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->image,
            $user->social_id,
            $user->social_name,
            $user->email_verified_at,
            $user->status,
            $user->remember_token,
            $user->created_at,
            $user->updated_at,
        ];
        // return [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];
    }

    public function headings(): array
    {
        return [
            '#', 'Name', 'Email', 'Image', 'Social ID', 'Social name', 'Email verified at', 'Status', 'Remember Token', 'Created at', 'Updated at'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:D1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'italic' => true
                    ],

                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['argb' => 'FFFF0000']
                        ]
                    ],
                ]);
            }
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(\public_path('/storage/images/user/HfSbp754Z4Ori7NizLCyzLGaJqL5ZJExkICnMc9g.jpg'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        return $sheets;
    }
}
