<?php

namespace App\Http\Controllers;

use App\Models\Barge;
use App\Models\Company;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\FingerTool;
use App\Models\Location;
use App\Models\Position;
use App\Models\Finger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use PDF;
use App\Exports\LaporanMutasiExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Sheets\EmployeePositionSheet;
use App\Exports\Sheets\EmployeeLocationSheet;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\DataTables;
// use Yajra\DataTables\DataTables;
use App\DataTables\EmployeesDataTable;
use App\DataTables\EmployeesExpDataTable;
use App\Models\Departmen;
use App\Models\salaryAdjustment;
use App\Models\salaryAdjustmentDetail;
use App\Models\TanggalMerah;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;


class TanggalMerahController extends Controller
{


    public function index(Datatables $datatables)
    {
        // return "a";
        $columns = [

   
            'tanggal' => ['name' => 'tanggal', 'title' => 'Tanggal'],
            'keterangan' => ['name' => 'keterangan', 'title' => 'Keterangan'],

            'aksi' => [
                'title' => "Aksi", 'orderable' => false, 'searchable' => false, 'printable' => false, 'class' => 'text-center', 'exportable' => false
            ],
        ];

        if ($datatables->getRequest()->ajax()) {
            $tanggal_merahs = TanggalMerah::query()
                ->select(
                    'tanggal_merahs.id',
                    'tanggal_merahs.tanggal',
                    'tanggal_merahs.keterangan',
                );

            return $datatables->eloquent($tanggal_merahs)
                ->addColumn('tanggal', function (TanggalMerah $data) {
                    return Carbon::parse($data->tanggal)->format('d F Y');
                })
                ->addColumn('keterangan', function (TanggalMerah $data) {
                    return $data->keterangan;
                })

                ->addColumn('aksi', function (TanggalMerah $data) {
                    $button = '<div><div class="btn-group">';

                    if (auth()->user()->can('download payroll')) {
                        $button .= '<a href="javascript:void(0)" data-download="' . url()->current() . "/export?a=" . $data->rekap_last_excel . '" class="btn-download btn btn-sm btn-success me-2"><i class="bi bi-filetype-csv"></i></a>';
                    }

    

                    $button .= '</div><div>';



                    return $button;
                })
                ->rawColumns(['aksi'])
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


        $period_payrolls = TanggalMerah::all();

        $compact = compact('html', 'period_payrolls');

        return view("pages.master.tanggal_merah.index", $compact);
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

    function store() {
        $tanggal  = request()->get('tanggal');
        $keterangan  = request()->get('keterangan');

        $tanggal_merah  = TanggalMerah::create([
            'tanggal'=>$tanggal,
            'keterangan'=>$keterangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Sukses",
        ], 200);
    }


    function destroy($id) {
        

        TanggalMerah::where('id',$id)->delete();

        return response()->json([
            'success' => true,
            'message' => "Sukses",
        ], 200);
    }
}
