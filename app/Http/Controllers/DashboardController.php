<?php

namespace App\Http\Controllers;

use App\Exports\DashboardExport;
use App\Models\AttendanceHasEmployee;
use App\Models\DashboardHasPosition;
use App\Models\Employee;
use App\Models\JobOrderHasEmployee;
use App\Models\Position;
use App\Models\VwEmployeeAbsenceLate;
use App\Models\WorkingHour;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $vue = true;
        $baseUrl = Url::to('/');
        $user = auth()->user();

        return view("pages.dashboard.index", compact("vue", "user", "baseUrl"));
    }

    public function fetchTotal()
    {
        $dateNow = Carbon::now()->format("Y-m-d");
        // $dateNow = Carbon::parse('2023-07-07')->format("Y-m-d");

        $workingHour = WorkingHour::first();
        $totalEmployee = Employee::active()->count();
        $queryAttendanceHasEmployee = AttendanceHasEmployee::whereDate("date", $dateNow)
            ->whereNotNull("employee_id")
            ->whereHas("employee", function ($query) {
                $query->where("employee_status", "aktif");
            })
            ->distinct("employee_id");
        $queryEmployeeNotCombackAfterRest = clone $queryAttendanceHasEmployee;
        $queryEmployeeAbsence = clone $queryAttendanceHasEmployee;
        $queryEmployeeAbsenceLate = VwEmployeeAbsenceLate::whereDate("date", $dateNow);

        $queryEmployeeNotCombackAfterRest = $queryEmployeeNotCombackAfterRest->whereNotNull("hour_rest_start")->whereNull("hour_rest_end");
        $dataNotCombackAfterRests = $queryEmployeeNotCombackAfterRest->get();
        $totalNotCombackAfterRest = $queryEmployeeNotCombackAfterRest->count();

        $dataEmployeeIdAbsences = $queryEmployeeAbsence->pluck("employee_id");
        $dataEmployeeAbsences = $queryEmployeeAbsence->get();
        $totalEmployeeAbsence = $queryEmployeeAbsence->count();

        // start not absence
        $queryEmployeeNotAbsence = Employee::active()
            ->select("id", "name", "position_id")
            ->whereNotIn("id", $dataEmployeeIdAbsences);

        $dataEmployeeNotAbsences = $queryEmployeeNotAbsence->get();
        $totalEmployeeNotAbsence = $queryEmployeeNotAbsence->count();
        // end not absence

        // start late present
        $dataEmployeeAbsenceLate = $queryEmployeeAbsenceLate->get();
        $totalEmployeeAbsenceLate = $queryEmployeeAbsenceLate->count();
        // end late present


        return response()->json([
            "success" => true,
            "dateNow" => $dateNow,
            // "hourMaxLatePresent" => $hourMaxLatePresent,
            // total
            "totalEmployee" => $totalEmployee,
            "totalEmployeeAbsence" => $totalEmployeeAbsence,
            "totalNotCombackAfterRest" => $totalNotCombackAfterRest,
            "totalEmployeeNotAbsence" => $totalEmployeeNotAbsence,
            "totalEmployeeAbsenceLate" => $totalEmployeeAbsenceLate,
            // data
            "dataEmployeeAbsences" => $dataEmployeeAbsences,
            "dataEmployeeNotAbsences" => $dataEmployeeNotAbsences,
            "dataNotCombackAfterRests" => $dataNotCombackAfterRests,
            "dataEmployeeAbsenceLate" => $dataEmployeeAbsenceLate,
        ]);
    }

    public function fetchTable()
    {
        // $dateNowSimulation = Carbon::now()->format("Y-m-11");
        $dateNow = Carbon::now();

        $dashboardHasPosition = DashboardHasPosition::pluck("position_id");
        $employeeHaveJobOrder = JobOrderHasEmployee::whereDate("datetime_start", $dateNow)->pluck("employee_id");
        $employeeNotYetJobOrders = Employee::active()
            ->select("id", "name", "position_id")
            ->whereIn("position_id", $dashboardHasPosition)
            ->whereNotIn("id", $employeeHaveJobOrder)
            ->get();

        $fiveEmployeeHighestJobOrders = JobOrderHasEmployee::whereYear("datetime_start", $dateNow->format("Y"))
            ->whereMonth("datetime_start", $dateNow->format("m"))
            ->select(DB::raw("count(id) as total, employee_id"))
            ->groupBy("employee_id")
            ->orderBy("total", "desc")
            ->limit(5)
            ->get();

        $totalEmployeeBaseOnPositions = Position::where('minimum_employee', '>', 0)->get();
        $totalEmployeeBaseOnPositions->map(function ($query) use ($dateNow) {
            $positionId = $query->id;
            $query["actual"] = AttendanceHasEmployee::whereHas('employee', function ($queryEmployee) use ($positionId) {
                $queryEmployee->where("position_id", $positionId);
            })
                ->whereDate("date", $dateNow)
                ->count();
        });

        return response()->json([
            "success" => true,
            "employeeNotYetJobOrders" => $employeeNotYetJobOrders,
            "fiveEmployeeHighestJobOrders" => $fiveEmployeeHighestJobOrders,
            "totalEmployeeBaseOnPositions" => $totalEmployeeBaseOnPositions,
        ]);
    }

    public function fetchHasPosition()
    {
        $data = DashboardHasPosition::all();

        return response()->json([
            "success" => true,
            "data" => $data,
        ]);
    }

    public function export()
    {
        $employeeNotYetJobOrders = $this->fetchTable()->original["employeeNotYetJobOrders"];
        $fiveEmployeeHighestJobOrders = $this->fetchTable()->original["fiveEmployeeHighestJobOrders"];
        $totalEmployeeBaseOnPositions = $this->fetchTable()->original["totalEmployeeBaseOnPositions"];
        $date = Carbon::now();
        $dateReadable = $date->isoFormat("dddd, D MMMM YYYY");
        $nameFile = "export/dashboard_{$dateReadable}.xlsx";

        try {
            $path = public_path($nameFile);

            if ($path) {
                @unlink($path);
            }

            Excel::store(new DashboardExport(
                $employeeNotYetJobOrders,
                $fiveEmployeeHighestJobOrders,
                $totalEmployeeBaseOnPositions,
            ), $nameFile, 'real_public', \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                "success" => true,
                // "data" => $data,
                "linkDownload" => route('dashboard.download', ["path" => $nameFile]),
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            // $routeAction = Route::currentRouteAction();
            // $log = new LogController;
            // $log->store(request("user_id"), $e->getMessage(), $routeAction);

            return response()->json([
                'success' => false,
                'message' => 'Gagal export data',
            ], 500);
        }
    }

    public function download()
    {
        $path = public_path(request("path"));

        return FacadesResponse::download($path);
    }

    public function storeHasPosition()
    {
        $checkExistsData = DashboardHasPosition::where("position_id", request("position_id"));

        if ($checkExistsData->count() > 0) {
            $position = Position::find(request("position_id"));

            return response()->json([
                'success' => false,
                'message' => "Maaf, jabatan {$position->name} sudah ada.",
            ], 409);
        }

        try {
            DB::beginTransaction();

            $message = "ditambahkan";
            $dashboardHasPosition = new DashboardHasPosition;
            $dashboardHasPosition->position_id = request("position_id");
            $dashboardHasPosition->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [],
                'message' => "Berhasil {$message}",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);


            return response()->json([
                'success' => false,
                'message' => "Gagal {$message}",
            ], 500);
        }
    }

    public function destroyHasPosition()
    {
        try {
            DB::beginTransaction();

            $dashboardHasPosition = DashboardHasPosition::where("position_id", request("position_id"));
            $dashboardHasPosition->delete();

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
}
