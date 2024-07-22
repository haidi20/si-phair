<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Vacation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// vacation = cuti
class VacationController extends Controller
{
    public function index()
    {

        // return Employee::where('id',1)->get();
        $vue = true;
        $baseUrl = Url::to('/');
        $user = auth()->user();

        //test

        return view("pages.vacation.index", compact("vue", "user", "baseUrl"));
    }

    public function fetchData()
    {
        $search = request("search");
        $month = Carbon::parse(request("month"));
        $monthReadAble = $month->isoFormat("MMMM YYYY");

        $vacations = Vacation::whereYear("date_start", $month->format("Y"))
            ->whereMonth("date_start", $month->format("m"));

        if ($search != null) {
            $vacations = $vacations->where(function ($query) use ($search) {
                $query->whereHas("employee", function ($employeeQuery) use ($search) {
                    $employeeQuery->where("name", "like", "%" . $search . "%");
                })->orWhereHas("creator", function ($creatorQuery) use ($search) {
                    $creatorQuery->where("name", "like", "%" . $search . "%");
                });
            });
        }

        $vacations = $vacations->get();

        return response()->json([
            "vacations" => $vacations,
            "monthReadAble" => $monthReadAble,
        ]);
    }

    public function store(Request $request)
    {
        // return request()->all();

        try {
            DB::beginTransaction();

            if (request("id")) {
                $vacation = Vacation::find(request("id"));

                $message = "diperbaharui";
            } else {
                $vacation = new Vacation;

                $message = "ditambahkan";
            }

            $vacation->employee_id = request("employee_id");
            $vacation->date_start = request("date_start");
            $vacation->date_end = request("date_end");
            $vacation->note = request("note");
            $vacation->save();

            DB::commit();

            return response()->json([
                'success' => true,
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

    public function storeApproval()
    {
        // return response()->json([
        //     'success' => true,
        //     'request' => request()->all(),
        //     'message' => check data",
        // ], 200);

        $rosterController = new RosterController;
        try {
            DB::beginTransaction();

            $vacation = Vacation::find(request("id"));
            $vacation->status = request("status");
            $vacation->date_start = request("date_vacation_start");
            $vacation->date_end = request("date_vacation_end");
            $vacation->save();

            $data = (object) [
                "date_vacation" => [
                    request("date_vacation_start"),
                    request("date_vacation_end"),
                ],
                "month" => request("date_start"),
                "employee_id" => request("employee_id"),
                "position_id" => request("position_id"),
            ];

            if (request("status") == 'accept') {
                $rosterController->storeWorkDay($data, true);
                $rosterController->storeRoster($data);
                $rosterController->storeVacation($data);
            } else if (request("status") == 'reject') {
                $rosterController->destroyVacation($data);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [],
                'message' => "Berhasil persetujuan cuti",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);

            return response()->json([
                'success' => false,
                'message' => "Gagal persetujuan cuti",
            ], 500);
        }
    }

    public function destroy()
    {
        try {
            DB::beginTransaction();

            $vacation = Vacation::find(request("id"));
            $vacation->update([
                'deleted_by' => request("user_id"),
            ]);
            $vacation->delete();

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
