<?php

namespace App\Http\Controllers;

use App\Models\ImageHasParent;
use App\Models\JobStatusHasParent;
use App\Models\JobStatusHasParentHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class JobStatusController extends Controller
{
    public function fetchDataBaseJobOrder()
    {
        $jobOrderId = request("job_order_id");
        $jobStatusHasParents = JobStatusHasParent::with("images")
            ->where([
                "parent_id" => $jobOrderId,
                "parent_model" => "App\Models\JobOrder"
            ])->get();

        return response()->json([
            "jobStatusHasParents" => $jobStatusHasParents,
            "requests" => request()->all(),
        ]);
    }

    public function fetchDataOvertimeBaseUser()
    {
        $overtimes = [];
        $month = Carbon::parse(request("month"));
        $employeeId = User::find(request("user_id"))->employee_id;

        if ($employeeId != null) {
            $overtimes = JobStatusHasParent::where("employee_id", $employeeId)
                ->where("status", "overtime")
                ->whereNotNull("datetime_end");

            if (request("is_date_filter") == "true") {
                $overtimes = $overtimes->whereDate("datetime_start", request("date"));
            } else {
                $overtimes = $overtimes->whereYear("datetime_start", $month->format("Y"))
                    ->whereMonth("datetime_start", $month->format("m"));
            }

            $overtimes = $overtimes->orderBy("datetime_start", "desc")->get();
        }

        return response()->json([
            "overtimes" => $overtimes,
            "requests" => request()->all(),
        ]);
    }

    public function fetchDataOvertimeBaseEmployee()
    {
        $overtimes = [];
        $month = Carbon::parse(request("month"));
        $employeeId = User::find(request("user_id"))->employee_id;

        if ($employeeId != null) {
            $overtimes = JobStatusHasParent::where("employee_id", "!=", $employeeId)
                ->where("created_by", request("user_id"))
                ->where("status", "overtime")
                ->whereNotNull("datetime_end");

            if (request("is_date_filter") == "true") {
                $overtimes = $overtimes->whereDate("datetime_start", request("date"));
            } else {
                $overtimes = $overtimes->whereYear("datetime_start", $month->format("Y"))
                    ->whereMonth("datetime_start", $month->format("m"));
            }

            $overtimes = $overtimes->orderBy("datetime_start", "desc")->get();
        }

        return response()->json([
            "overtimes" => $overtimes,
            "requests" => request()->all(),
        ]);
    }

    public function downloadImage()
    {
        try {
            $imageHasParent = ImageHasParent::find(request("image_has_parent_id"));
            $path = $imageHasParent->path_name;
            $name = $imageHasParent->name;

            return response()->download(
                storage_path($path),
                $name
            );
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);


            return response()->json([
                'success' => false,
                'message' => 'Maaf, gagal download gambar'
            ], 500);
        }
    }

    public function storeJobStatusHasParent($parent, $statusLast = null, $datetime, $isOvertimeRest, $nameModel)
    {
        // $statusLast = request("status_last");
        $parentNote = null;
        if (isset($parent["status_note"])) {
            $parentNote = $parent["status_note"];
        }

        if ($statusLast != null) {

            $jobStatusHasParent = JobStatusHasParent::where([
                "status" => $statusLast,
                "parent_id" => $parent->id,
                "parent_model" => $nameModel,
            ])->orderBy("created_at", "desc")->first();

            if ($jobStatusHasParent) {
                $getValidationDatetime = $this->getValidationDatetime($jobStatusHasParent->datetime_start, $datetime);

                if ($getValidationDatetime) {
                    $datetimeStart = dateTimeReadable($jobStatusHasParent->datetime_start);
                    $datetimeEnd = dateTimeReadable($datetime);
                    $message = "Maaf, waktu selesai tidak boleh kurang dari waktu mulai. <br> ";
                    $message .= "Mulai = {$datetimeStart} <br> selesai = {$datetimeEnd}";

                    return (object) [
                        'error' => true,
                        'data' => [],
                        'message' =>  $message,
                    ];
                } else {
                    $jobStatusHasParent->update([
                        "note_end" => $parentNote,
                        "is_overtime_rest" => $isOvertimeRest,
                        "datetime_end" => $datetime,
                    ]);

                    $this->storeJobStatusHasParentHistory($jobStatusHasParent, false);
                }
            }
        } else {
            $jobStatusHasParent = new JobStatusHasParent;
            $jobStatusHasParent->parent_id = $parent->id;
            $jobStatusHasParent->parent_model = $nameModel;
            $jobStatusHasParent->job_order_id = $parent->id;
            $jobStatusHasParent->status = $parent->status;
            $jobStatusHasParent->datetime_start = $datetime;
            $jobStatusHasParent->note_start = $parentNote;

            if ($nameModel == "App\Models\JobOrderHasEmployee") {
                $jobStatusHasParent->job_order_id = $parent->job_order_id;
                $jobStatusHasParent->employee_id = $parent->employee_id;
                $jobStatusHasParent->is_overtime_rest = $isOvertimeRest;
            }

            $jobStatusHasParent->save();

            $this->storeJobStatusHasParentHistory($jobStatusHasParent, false);
        }

        return (object) [
            'error' => false,
            'data' => $jobStatusHasParent,
        ];
    }

    public function storeOvertime()
    {
        $user = User::find(request("user_id"));
        $datetimeStart = Carbon::parse(request("date_start") . request("hour_start"));
        $datetimeEnd = Carbon::parse(request("date_end") . request("hour_end"));
        $userEmployeeId = $user ? $user->employee_id : null;
        $employeeId = request("employee_id", $userEmployeeId);

        $getStoreValidation = $this->storeOvertimeValidation($user, $employeeId);

        if ($getStoreValidation) {
            return response()->json([
                'success' => false,
                'request' => request()->all(),
                'message' => $this->storeOvertimeValidation($user, $employeeId, "result"),
            ], 400);
        }

        try {
            DB::beginTransaction();

            if (request("id")) {
                $jobStatusHasParent = JobStatusHasParent::find(request("id"));

                $message = "Diperbaharui";
            } else {
                $jobStatusHasParent = new JobStatusHasParent;

                $message = "Ditambahkan";
            }

            $jobStatusHasParent->employee_id = $employeeId;
            $jobStatusHasParent->status = "overtime";
            $jobStatusHasParent->datetime_start = $datetimeStart;
            $jobStatusHasParent->datetime_end = $datetimeEnd;
            $jobStatusHasParent->note_start = request("note");
            $jobStatusHasParent->is_overtime_rest = request("is_overtime_rest");
            $jobStatusHasParent->save();

            $this->storeJobStatusHasParentHistory($jobStatusHasParent, false);

            DB::commit();
            return response()->json([
                'success' => true,
                'request' => request()->all(),
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
                'message' => "Maaf, Gagal {$message}",
            ], 500);
        }
    }

    public function storeOvertimeRevision()
    {
        $datetimeStart = Carbon::parse(request("datetime_start"));
        $datetimeEnd = Carbon::parse(request("datetime_end"));

        if ($datetimeStart->greaterThan($datetimeEnd)) {
            return response()->json([
                'success' => false,
                'message' => "Maaf, Waktu mulai lembur lebih besar dari waktu selesai lembur",
            ], 500);
        }

        try {
            DB::beginTransaction();

            $jobStatusHasParent = JobStatusHasParent::find(request("id"));
            $jobStatusHasParent->datetime_start = request("datetime_start");
            $jobStatusHasParent->datetime_end = request("datetime_end");
            $jobStatusHasParent->save();

            $this->storeJobStatusHasParentHistory($jobStatusHasParent, false);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Perbaharui Data'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);


            return response()->json([
                'success' => false,
                'message' => 'Maaf, gagal Perbaharui data'
            ], 500);
        }
    }

    public function updateJobStatusHasParent($parent, $nameModel)
    {
        $jobStatusHasParent = JobStatusHasParent::where([
            "parent_id" => $parent->id,
            "parent_model" => $nameModel,
            "status" => $parent->status,
            "datetime_end" => null
        ])->orderBy("created_at", "desc")->first();

        $jobStatusHasParent->datetime_start = $parent->datetime_start;
        $jobStatusHasParent->note_start = $parent->note_start;
        $jobStatusHasParent->save();

        return (object) [
            'error' => false,
            'data' => $jobStatusHasParent,
        ];
    }

    public function destroyJobStatusHasParent()
    {
        try {
            DB::beginTransaction();

            $jobStatusHasParent = JobStatusHasParent::find(request("id"));
            $jobStatusHasParent->update([
                'deleted_by' => request("user_id"),
            ]);
            $jobStatusHasParent->delete();

            $this->storeJobStatusHasParentHistory($jobStatusHasParent, true);

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

    public function destroyJobStatusHasParentBaseJobOrder($jobOrder, $nameModel)
    {
        $jobStatusHasParent = JobStatusHasParent::where([
            "parent_id" => $jobOrder->id,
            "parent_model" => $nameModel,
        ]);
        $jobStatusHasParent->update([
            'deleted_by' => request("user_id"),
        ]);

        foreach ($jobStatusHasParent->get() as $index => $item) {
            $getData = JobStatusHasParent::find($item->id);

            $this->storeJobStatusHasParentHistory($getData, true);
        }

        $jobStatusHasParent->delete();
    }

    private function storeJobStatusHasParentHistory($jobStatusHasParent, $isDelete = false)
    {
        $jobStatusHasParentHistory = new JobStatusHasParentHistory;
        $jobStatusHasParentHistory->job_status_has_parent_id = $jobStatusHasParent->id;
        $jobStatusHasParentHistory->parent_id = $jobStatusHasParent->parent_id;
        $jobStatusHasParentHistory->parent_model = $jobStatusHasParent->parent_model;
        $jobStatusHasParentHistory->status = $jobStatusHasParent->status;
        $jobStatusHasParentHistory->datetime_start = $jobStatusHasParent->datetime_start;
        $jobStatusHasParentHistory->datetime_end = $jobStatusHasParent->datetime_end;
        $jobStatusHasParentHistory->note_start = $jobStatusHasParent->note_start;
        $jobStatusHasParentHistory->note_end = $jobStatusHasParent->note_end;
        $jobStatusHasParentHistory->is_overtime_rest = $jobStatusHasParent->is_overtime_rest;
        $jobStatusHasParentHistory->deleted_by = $jobStatusHasParent->deleted_by;

        if ($isDelete) {
            $jobStatusHasParentHistory->deleted_at = Carbon::now();
        }

        $jobStatusHasParentHistory->save();
    }

    private function getValidationDatetime($start, $end)
    {
        if ($end < $start) {
            return true;
        }

        return false;
    }

    private function storeOvertimeValidation($user, $employeeId, $type = null)
    {
        $isError = false;
        $message = null;

        $intervalMinutes = 30;
        $datetimeStart = Carbon::parse(request("date_start") . request("hour_start"));
        $datetimeEnd = Carbon::parse(request("date_end") . request("hour_end"));

        $dataExists = JobStatusHasParent::selectRaw('*, ABS(TIMESTAMPDIFF(MINUTE, datetime_start, ?)) as differentTime', [$datetimeStart])
            ->where(["employee_id" => $employeeId, 'status' => 'overtime'])
            ->where(function ($query) use ($datetimeStart, $intervalMinutes) {
                $query
                    ->whereRaw("DATE_FORMAT(datetime_start, ?) = ?", ['%Y-%m-%d %H', $datetimeStart->format('Y-m-d H')])
                    ->orWhere(function ($query) use (
                        $datetimeStart,
                        $intervalMinutes
                    ) {
                        $query->whereRaw("ABS(TIMESTAMPDIFF(MINUTE, datetime_start, ?)) <= ?", [$datetimeStart, $intervalMinutes]);
                    });
            });

        $yearNow = Carbon::now()->format("Y");

        if (request("user_id") == null) {
            $isError = true;
            $message = "Maaf, anda harus login ulang";
        } else if ($datetimeStart->greaterThan($datetimeEnd)) {
            $isError = true;
            $message = "Maaf, Waktu mulai lembur lebih besar dari waktu selesai lembur";
        } else if ($user && $user->employee_id == null && request("employee_id") == null) {
            $isError = true;
            $message = "Maaf, akun anda belum di ketahui data karyawan";
        } else if (request("hour_start") == null || request("hour_end") == null) {
            $isError = true;
            $message = "Maaf, jam tidak boleh kosong";
        } else if (request("id") == null && $dataExists->count() > 0) {
            $data = $dataExists->first();
            $isError = true;
            $message = "Maaf, data sudah ada. di isi oleh {$data->creator->name} di waktu lembur {$data->datetime_start_readable}";
        } else if ($yearNow != $datetimeStart->format("Y")) {
            $isError = true;
            $message = "Maaf, tahun tidak sama dengan tahun sekarang";
        }

        if ($type == "result") {
            return $message;
        }

        return $isError;
    }
}
