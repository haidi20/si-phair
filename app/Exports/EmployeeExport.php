<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

// WithTitle
class EmployeeExport extends DefaultValueBinder  implements FromView, ShouldAutoSize, WithStyles, WithDrawings, WithCustomValueBinder
{
    protected $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    // public function title(): string
    // {
    //     return '5 Karyawan Dengan Job Order Terbanyak';
    // }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('logo');
        $drawing->setDescription('This is logo');
        $drawing->setPath(public_path('/assets/img/logo.png'));
        $drawing->setHeight(45);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function view(): View
    {
        return view('pages.master.employee.exports.main', [
            'data' => $this->data,
        ]);
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }

    public function styles(Worksheet $sheet)
    {
        // $range = 'A2:A10';
        // $style = [
        //     'alignment' => [
        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //         'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //         'wrapText' => true,
        //     ],
        // ];
        // $sheet->getStyle($range)->applyFromArray($style);
    }
}
