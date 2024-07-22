<?php

namespace App\Exports;

use App\Models\AttendancePayrol;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\WithTitle;

use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;


class RekapGajiPayrollExportPerEmployee implements FromView,WithStyles
{

    protected  $period_payroll= null;
    protected  $type_employe= null;

    protected  $nrow= 0;

    public function __construct($period_payroll,$type_employe) {
        $this->period_payroll = $period_payroll;
        $this->type_employe = $type_employe;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {

        if($this->type_employe == 'all'){
            $payrolls = Employee::where('employee_status','aktif')->where('period_payroll_id',$this->period_payroll->id)
            ->join('payrolls','employees.id','payrolls.employee_id')
            ->where('payrolls.period_payroll_id',$this->period_payroll->id)
            ->orderBy('employees.position_id','asc')
            ->get();
        }

        if($this->type_employe == 'cv'){
            $payrolls = Employee::where('employee_status','aktif')->where('period_payroll_id',$this->period_payroll->id)
            ->join('payrolls','employees.id','payrolls.employee_id')
            ->where('payrolls.period_payroll_id',$this->period_payroll->id)
            ->where('employees.company_id', '2')
            ->orderBy('employees.position_id','asc')
            ->get();

            
            
        }

        if($this->type_employe == 'pt'){
            $payrolls = Employee::where('employee_status','aktif')->where('period_payroll_id',$this->period_payroll->id)
            ->join('payrolls','employees.id','payrolls.employee_id')
            ->where('payrolls.period_payroll_id',$this->period_payroll->id)
            ->where('employees.company_id', '1')
            ->orderBy('employees.position_id','asc')
            ->get();
        }

        $jabatan = '';
        $nrow = 1;
        foreach ($payrolls as $key => $p) {
            if ($jabatan != $p->position_name){
                $jabatan = $p->position_name;
                $nrow++;
            }
            $nrow++;
        }

        $this->nrow = $nrow;


        return view('pages.period_payroll.rekap_gaji_exports', [
            'period_payroll' => $this->period_payroll,
            'payrolls' => $payrolls,
            'type_employe'=>$this->type_employe,
            // 'attendance'=>AttendancePayrol::where('payroll_id',Payroll::where('period_payroll_id',$this->period_payroll->id)->where('employee_id',$this->employee->id)->first()->id)->orderBy('date','asc')->get(),
        ]);
    }

    public function styles(Worksheet $sheet)
    {


        $Border = array(
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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


        $sheet->getStyle('A1:S4')->applyFromArray($Border,false);

        $sheet->getStyle('A5:S'.($this->nrow+7))->applyFromArray($Border1,false);
        // $sheet->
        
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setWidth(5);
        $sheet->getStyle('D2:D4')->getAlignment()->setWrapText(true); 


        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);

        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);

        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);
        $sheet->getColumnDimension('S')->setAutoSize(true);
        // $sheet->getColumnDimension('D')->setAutoSize(true);
        // $sheet->getColumnDimension('E')->setAutoSize(true);
        // $sheet->getColumnDimension('F')->setAutoSize(true);
        // $sheet->getColumnDimension('G')->setAutoSize(true);
        // $sheet->getColumnDimension('H')->setAutoSize(true);

        // $sheet->getStyle('A5:S'.($this->nrow+5))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);




        



        // $sheet->getStyle('B7:L7')->getFill()->applyFromArray(['fillType' => 'solid','rotation' => 0, 'color' => ['rgb' => 'D9D9D9'],'border'=>1]);
        // $sheet->setpaperSize(1);
    }

    public function registerEvents(): array
    {
        return [
            BeforeExport::class  => function(BeforeExport $event) {
                // Macro
                $event->writer->setCreator('M Nur Alfiansyah');
                
                // Or via magic __call
                $event->writer
                ->getProperties()
                ->setCreator('M Nur Alfiansyah');
            },
            AfterSheet::class    => function(AfterSheet $event) {
                // Macro
                // $event->sheet
                // ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                
                // Or via magic __call
                $event->sheet
                ->getPageSetup()
                ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
            },
        ];
    }



    // public function title(): string
    // {
    //     return $this->employee->name ?? 'no-name';
    // }
}
