<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class EmployeeLocationSheet implements FromView, WithTitle, ShouldAutoSize, WithStyles, WithDrawings
{
    protected $employees;
    protected $location_id;

    function __construct($data)
    {
        $this->employees = $data['employees'];
        $this->location_id = $data['location_id'];
    }

    public function title(): string
    {
        return 'UTAMA';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('logo');
        $drawing->setDescription('This is logo');
        $drawing->setPath(public_path('/assets/img/logo.png'));
        $drawing->setHeight(45);
        $drawing->setCoordinates('B1');

        return $drawing;
    }

    public function view(): View
    {
        return view('pages.master.employee.exports.main-location', [
            'employees' => $this->employees,
            'location_id' => $this->location_id,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $range = 'A2:A10';
        $style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
        $sheet->getStyle($range)->applyFromArray($style);
    }
}
