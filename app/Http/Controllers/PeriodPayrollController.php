<?php

namespace App\Http\Controllers;

// use ___PHPSTORM_HELPERS\object;
use Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PayrollExport;
use App\Exports\RekapGajiPayrollExportPerEmployee;
use App\Models\Attendance;
use App\Models\AttendanceHasEmployee;
use App\Models\AttendancePayrol;
use App\Models\BaseWagesBpjs;
use App\Models\BpjsCalculation;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PeriodPayroll;
use App\Models\RosterDaily;
use App\Models\salaryAdjustmentDetail;
use App\Models\SalaryAdvanceDetail;
use App\Models\Vacation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use Carbon\CarbonPeriod;
// use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;


use App\Models\JobStatusHasParent;

class PeriodPayrollController extends Controller
{
    public $period_payrol_month_year;
    public function __construct($period_payrol_month_year = [])
    {
        $this->period_payrol_month_year = $period_payrol_month_year;
        // print("\nFUNGSI IN \n");
        // if (count($period_payrol_month_year) == 0) {
        //     $this->period_payrol_month_year = $period_payrol_month_year;
        // } else {
        //     $this->period_payrol_month_year = (object) $period_payrol_month_year;
        // }
    }
    public function index(Datatables $datatables)
    {
        // return "a";
        $columns = [

            // name
            // number_of_workdays




            'id' => ['title' => 'No.', 'orderable' => false, 'searchable' => false, 'render' => function () {
                return 'function(data,type,fullData,meta){return meta.settings._iDisplayStart+meta.row+1;}';
            }],
            'name_period' => ['name' => 'name', 'title' => 'Periode'],
            // 'date_start' => ['name' => 'date_start', 'title' => 'Tanggal Awal Kerja'],
            // 'date_end' => ['name' => 'date_end', 'title' => 'Tanggal Akhir Kerja'],
            'slip_gaji' => [
                'title' => "Slip Gaji", 'orderable' => false, 'width' => '110px', 'searchable' => false, 'printable' => false, 'class' => 'text-center', 'width' => '50%', 'exportable' => false
            ],
            'rekap_gaji' => [
                'title' => "Rekap Gaji", 'orderable' => false, 'width' => '110px', 'searchable' => false, 'printable' => false, 'class' => 'text-center', 'width' => '50%', 'exportable' => false
            ],
        ];

        if ($datatables->getRequest()->ajax()) {
            $period_payroll = PeriodPayroll::query()
                ->select('period_payrolls.last_excel', 'period_payrolls.period', 'period_payrolls.id', 'period_payrolls.name', 'period_payrolls.date_start', 'period_payrolls.date_end', 'period_payrolls.number_of_workdays'
            ,"period_payrolls.last_excel","period_payrolls.last_excel_cv_kpt","period_payrolls.last_excel_pt_kpt","period_payrolls.last_pdf","period_payrolls.last_pdf_pt_kpt","period_payrolls.last_pdf_cv_kpt",
            "period_payrolls.rekap_last_excel","period_payrolls.rekap_last_excel_cv_kpt","period_payrolls.rekap_last_excel_pt_kpt","period_payrolls.rekap_last_pdf","period_payrolls.rekap_last_pdf_cv_kpt","period_payrolls.rekap_last_pdf_pt_kpt"
            );

            return $datatables->eloquent($period_payroll)
                ->filterColumn('name', function (Builder $query, $keyword) {
                    $sql = "period_payrolls.name  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->removeColumn(['last_excel', 'period', 'name', 'number_of_workdays'])
                ->addColumn('name_period', function (PeriodPayroll $data) {
                    return Carbon::parse($data->period)->format('F Y');
                })
                // ->filterColumn('description', function (Builder $query, $keyword) {
                //     $sql = "period_payrolls.description like ?";
                //     $query->whereRaw($sql, ["%{$keyword}%"]);
                // })
                ->addColumn('slip_gaji', function (PeriodPayroll $data) {
                    $button = '<div><div class="btn-group">';

                    if (auth()->user()->can('download payroll')) {
                        $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->last_excel . '" class="btn-download btn btn-sm btn-success me-2"><i class="bi bi-filetype-csv"> PT & CV KPT</i></a>';
                        $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->last_excel_cv_kpt . '" class="btn-download btn btn-sm btn-success me-2"><i class="bi bi-filetype-csv"></i> CV KPT</a>';
                        $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->last_excel_pt_kpt . '" class="btn-download btn btn-sm btn-success me-2"><i class="bi bi-filetype-csv"></i> PT KPT</a>';
                        // $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->last_excel . '" class="btn-download btn btn-sm btn-danger me-2"><i class="bi bi-download"></i></a>';
                    }

                    $button .= '</div> <br><br>';

                    $button .= '<div class="btn-group">';

                    if (auth()->user()->can('download payroll')) {
                        // $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->last_pdf . '" class="btn-download btn btn-sm btn-danger me-2"><i class="bi bi-filetype-pdf"> PT & CV KPT</i></a>';
                        // $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->last_pdf_cv_kpt . '" class="btn-download btn btn-sm btn-danger me-2"><i class="bi bi-filetype-pdf"></i> CV KPT</a>';
                        // $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->last_pdf_pt_kpt . '" class="btn-download btn btn-sm btn-danger me-2"><i class="bi bi-filetype-pdf"></i> PT KPT</a>';
                        // $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->last_excel . '" class="btn-download btn btn-sm btn-warning me-2"><i class="bi bi-download"></i></a>';
                    }

                    $button .= '</div><div>';



                    return $button;
                })

                ->addColumn('rekap_gaji', function (PeriodPayroll $data) {
                    $button = '<div><div class="btn-group">';

                    if (true) {
                        $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->rekap_last_excel . '" class="btn-download btn btn-sm btn-success me-2"><i class="bi bi-filetype-csv"> PT & CV KPT</i></a>';
                        $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->rekap_last_excel_cv_kpt . '" class="btn-download btn btn-sm btn-success me-2"><i class="bi bi-filetype-csv"></i> CV KPT</a>';
                        $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->rekap_last_excel_pt_kpt . '" class="btn-download btn btn-sm btn-success me-2"><i class="bi bi-filetype-csv"></i> PT KPT</a>';
                        // $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->rekap_last_excel . '" class="btn-download btn btn-sm btn-warning me-2"><i class="bi bi-download"></i></a>';
                    }

                    $button .= '</div> <br><br>';

                    $button .= '<div class="btn-group">';

                    if (true) {
                        // $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->rekap_last_pdf . '" class="btn-download btn btn-sm btn-danger me-2"><i class="bi bi-filetype-pdf"> PT & CV KPT</i></a>';
                        // $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->rekap_last_pdf_cv_kpt . '" class="btn-download btn btn-sm btn-danger me-2"><i class="bi bi-filetype-pdf"></i> CV KPT</a>';
                        // $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->rekap_last_pdf_pt_kpt . '" class="btn-download btn btn-sm btn-danger me-2"><i class="bi bi-filetype-pdf"></i> PT KPT</a>';
                        // $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->last_excel . '" class="btn-download btn btn-sm btn-danger me-2"><i class="bi bi-download"></i></a>';
                    }

                    $button .= '</div><div>';



                    return $button;
                })
                ->rawColumns(['rekap_gaji', 'slip_gaji'])
                ->toJson();
        }

        $columnsArrExPr = [0, 1, 2, 3];
        $html = $datatables->getHtmlBuilder()
            ->columns($columns)
            ->parameters([
                'order' => [[1, 'desc']],
                'responsive' => true,
                'autoWidth' => false,
                'dom' => 'lfrtip',
                'lengthMenu' => [
                    [10, 25, 50, -1],
                    ['10 Data', '25 Data', '50 Data', 'Semua Data']
                ],
                // 'buttons' => $this->buttonDatatables($columnsArrExPr),
            ]);


        $period_payrolls = PeriodPayroll::all();

        $compact = compact('html', 'period_payrolls');

        return view("pages.period_payroll.index", $compact);
    }

    private function buttonDatatables($columnsArrExPr)
    {
        return [
            ['extend' => 'csv', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export CSV'],
            ['extend' => 'pdf', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export PDF'],
            ['extend' => 'excel', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Export Excel'],
            ['extend' => 'print', 'className' => 'btn btn-sm btn-secondary', 'text' => 'Print'],
        ];
    }

    public function fetchData()
    {
        $period_payrolls = PeriodPayroll::orderBy("period", "asc")->get();

        return response()->json([
            "period_payrolls" => $period_payrolls,
        ]);
    }


    public function store(PeriodPayroll $period_payroll,$employees)
    {

        try {
           /////////////////////////
           $unik_name_excel = '';
           $unik_name_pdf = '';

           $period_payroll->update([
            'last_excel' => $unik_name_excel,
            'last_excel_cv_kpt' => "pt_kpt_" . $unik_name_excel,
            'last_excel_pt_kpt' => "cv_kpt_" . $unik_name_excel,
            'last_pdf'=>"all_".$unik_name_pdf,
            'last_pdf_pt_kpt'=>"all_".$unik_name_pdf,
            'last_pdf_cv_kpt'=>"all_".$unik_name_pdf,



            ////
            'rekap_last_excel'=>'all_rekap_gaji'.$unik_name_excel,
            'rekap_last_excel_cv_kpt'=>'cv_rekap_gaji'.$unik_name_excel,
            'rekap_last_excel_pt_kpt'=>'pt_rekap_gaji'.$unik_name_excel,
            'rekap_last_pdf'=>'all_rekap_gaji'.$unik_name_pdf,
            'rekap_last_pdf_cv_kpt'=>'cv_rekap_gaji'.$unik_name_pdf,
            'rekap_last_pdf_pt_kpt'=>'pt_rekap_gaji'.$unik_name_pdf,

        ]);


           

            
            // $period_payroll = PeriodPayroll::where('')
            $unik_name_excel = 'Periode_' . $period_payroll->period . '_' . Carbon::now()->format('Y_m_d_H_i_s') . '.xlsx';
            $unik_name_pdf = 'Periode_' . $period_payroll->period . '_' . Carbon::now()->format('Y_m_d_H_i_s') . '.pdf';

            Excel::store(new PayrollExport($period_payroll, $employees), $unik_name_excel, 'local');
            Excel::store(new PayrollExport($period_payroll, $employees->where('company_id', '1')->values()), "pt_kpt_" . $unik_name_excel, 'local');
            Excel::store(new PayrollExport($period_payroll, $employees->where('company_id', '2')->values()), "cv_kpt_" . $unik_name_excel, 'local');

            Excel::store(new RekapGajiPayrollExportPerEmployee($period_payroll, 'all'), "all_rekap_gaji_" . $unik_name_excel, 'local');
            Excel::store(new RekapGajiPayrollExportPerEmployee($period_payroll, 'cv'), "cv_rekap_gaji_" . $unik_name_excel, 'local');
            Excel::store(new RekapGajiPayrollExportPerEmployee($period_payroll, 'pt'), "pt_rekap_gaji_" . $unik_name_excel, 'local');


            $period_payroll->update([
                'last_excel' => $unik_name_excel,
                'last_excel_cv_kpt' => "pt_kpt_" . $unik_name_excel,
                'last_excel_pt_kpt' => "cv_kpt_" . $unik_name_excel,


                // 'last_pdf'=>"all_".$unik_name_pdf,
                // 'last_pdf_pt_kpt'=>"pt_".$unik_name_pdf,
                // 'last_pdf_cv_kpt'=>"cv_".$unik_name_pdf,

                'last_pdf'=>"all_".$unik_name_pdf,
                'last_pdf_pt_kpt'=>"all_".$unik_name_pdf,
                'last_pdf_cv_kpt'=>"all_".$unik_name_pdf,



                ////
                'rekap_last_excel'=>'all_rekap_gaji_'.$unik_name_excel,
                'rekap_last_excel_cv_kpt'=>'cv_rekap_gaji_'.$unik_name_excel,
                'rekap_last_excel_pt_kpt'=>'pt_rekap_gaji_'.$unik_name_excel,

                'rekap_last_pdf'=>'all_rekap_gaji_'.$unik_name_pdf,
                'rekap_last_pdf_cv_kpt'=>'cv_rekap_gaji_'.$unik_name_pdf,
                'rekap_last_pdf_pt_kpt'=>'pt_rekap_gaji_'.$unik_name_pdf,

            ]);



 




            // Excel::store(new RekapGajiPayrollExportPerEmployee($period_payroll, 'all'), "all_rekap_gaji" . $unik_name_pdf, 'local', \Maatwebsite\Excel\Excel::DOMPDF);
            // Excel::store(new RekapGajiPayrollExportPerEmployee($period_payroll, 'cv'), "cv_rekap_gaji" . $unik_name_pdf, 'local', \Maatwebsite\Excel\Excel::DOMPDF);
            // Excel::store(new RekapGajiPayrollExportPerEmployee($period_payroll, 'pt'), "pt_rekap_gaji" . $unik_name_pdf, 'local', \Maatwebsite\Excel\Excel::DOMPDF);


            
            // PDF

            // $customPaper = array(0,0,567.00,283.80);

            // print("Generate Slip Gaji");

            // \ini_set('memory_limit','-1');
            // $data = compact('period_payroll','employees');
            // $pdf = PDF::loadView('pages.period_payroll.export_pdf_slip_gaji', $data)->setPaper('A4', 'landscape');

            // $pdf_cv = PDF::loadView('pages.period_payroll.export_pdf_slip_gaji',['period_payroll'=>$period_payroll,'employees'=>Employee::get()])->setPaper('A4', 'landscape');
            // $pdf_pt = PDF::loadView('pages.period_payroll.export_pdf_slip_gaji',['period_payroll'=>$period_payroll,'employees'=>Employee::get()])->setPaper('A4', 'landscape');


            // $employees = Employee::where('company_id',2)->get();
            // $data_cv = compact('period_payroll','employees');
            // $pdf_cv = PDF::loadView('pages.period_payroll.export_pdf_slip_gaji', $data_cv)->setPaper('A4', 'landscape');


            // $employees = Employee::where('company_id',1)->get();
            // $data_pt = compact('period_payroll','employees');
            // $pdf_pt = PDF::loadView('pages.period_payroll.export_pdf_slip_gaji', $data_pt)->setPaper('A4', 'landscape');

            // Stroage

            // Storage::disk('local')->put("all_".$unik_name_pdf, $pdf->output());


            print("SUUCESS GENERATED \n");


            return true;
            // return response()->json([
            //     'success' => true,
            //     'message' => "Berhasil {$message}",
            // ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            print_r([$e->getMessage(), $e->getLine()]);
            // print(json_encode($e->getTrace()));

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getTrace(), $routeAction);
            // DB::commit();

            return false;


            // return response()->json([
            //     'success' => false,
            //     'message' => "Gagal {$message} {$e->getMessage()}",
            //     'error' => [$e->getMessage(), $e->getTrace(), $e->getLine()]
            // ], 500);
        }
    }

    public function destroy()
    {
        try {
            DB::beginTransaction();

            $period_payroll = PeriodPayroll::find(request("id"));
            $period_payroll->update([
                'deleted_by' => Auth::user()->id,
            ]);
            $period_payroll->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);


            return response()->json([
                'success' => false,
                'message' => 'Gagal dihapus',
            ], 500);
        }
    }

    function export()
    {
        $path = storage_path('app/' . request()->get('a'));
        return response()->download($path);
    }
}
