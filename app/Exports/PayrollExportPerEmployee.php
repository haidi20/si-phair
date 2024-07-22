<?php

namespace App\Exports;

use App\Models\AttendancePayrol;
use App\Models\Payroll;
use App\Models\User;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class PayrollExportPerEmployee implements FromView,WithTitle,WithStyles
{

    protected  $period_payroll= null;
    protected  $employee= null;

    public function __construct($period_payroll,$employee) {
        $this->period_payroll = $period_payroll;
        $this->employee = $employee;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        // print("EMploye : ".$this->employee->id ."\n");
        return view('pages.period_payroll.exports', [
            'period_payroll' => $this->period_payroll,
            'employee' => $this->employee,

            'payroll' => Payroll::where('period_payroll_id',$this->period_payroll->id)->where('employee_id',$this->employee->id)->first(),
            'attendance'=>AttendancePayrol::
            select(
                'attendance.*',
                DB::raw("TIMESTAMPDIFF(MINUTE, attendance.hour_start, attendance.hour_overtime_end) AS jam_kerja_lembur_kotor"),
                DB::raw("TIMESTAMPDIFF(MINUTE, attendance.hour_start, attendance.hour_end) AS jam_kerja_kotor"),
                )
            ->where('payroll_id',Payroll::where('period_payroll_id',$this->period_payroll->id)->where('employee_id',$this->employee->id)->first()->id)->orderBy('date','asc')->get(),
        ]);
    }

    public function title(): string
    {
        return $this->employee->name ?? 'no-name';
    }

    public function styles(Worksheet $sheet)
    {


        // $Border = array(
        //     'alignment' => [
        //         'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        //         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        //     ],
        //     'borders' => [
        //         'right' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => ['argb' => '000000'],
        //         ],
        //         'left' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => ['argb' => '000000'],
        //         ],
        //         'bottom' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => ['argb' => '000000'],
        //         ],
        //         'top' => [
        //             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        //             'color' => ['argb' => '000000'],
        //         ],
        //     ],
        // );


        $Border1 = array(
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'right' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
                'left' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        );

        // $sheet->setQuotePrefix(true);


        // $sheet->getStyle('A1:S4')->applyFromArray($Border,false);

        $sheet->getStyle('B9:O41')->applyFromArray($Border1,false);
        // // $sheet->
        
        // $sheet->getColumnDimension('A')->setAutoSize(true);
        // $sheet->getColumnDimension('B')->setAutoSize(true);
        // $sheet->getColumnDimension('C')->setAutoSize(true);
        // $sheet->getColumnDimension('D')->setWidth(5);
        // $sheet->getStyle('D2:D4')->getAlignment()->setWrapText(true); 


        // $sheet->getColumnDimension('E')->setAutoSize(true);
        // $sheet->getColumnDimension('F')->setAutoSize(true);
        // $sheet->getColumnDimension('G')->setAutoSize(true);
        // $sheet->getColumnDimension('H')->setAutoSize(true);

        // $sheet->getColumnDimension('I')->setAutoSize(true);
        // $sheet->getColumnDimension('J')->setAutoSize(true);
        // $sheet->getColumnDimension('K')->setAutoSize(true);
        // $sheet->getColumnDimension('L')->setAutoSize(true);
        // $sheet->getColumnDimension('M')->setAutoSize(true);
        // $sheet->getColumnDimension('N')->setAutoSize(true);
        // $sheet->getColumnDimension('O')->setAutoSize(true);
        // $sheet->getColumnDimension('P')->setAutoSize(true);

        // $sheet->getColumnDimension('Q')->setAutoSize(true);
        // $sheet->getColumnDimension('R')->setAutoSize(true);
        // $sheet->getColumnDimension('S')->setAutoSize(true);
        // $sheet->getColumnDimension('D')->setAutoSize(true);
        // $sheet->getColumnDimension('E')->setAutoSize(true);
        // $sheet->getColumnDimension('F')->setAutoSize(true);
        // $sheet->getColumnDimension('G')->setAutoSize(true);
        // $sheet->getColumnDimension('H')->setAutoSize(true);

        // $sheet->getStyle('A5:S'.($this->nrow+5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);




        



        // $sheet->getStyle('B7:L7')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],'border'=>1]);
        // $sheet->setpaperSize(1);
    }
}
