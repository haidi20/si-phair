<?php

namespace App\Http\Controllers;

use App\Models\ImageHasParent;
use App\Models\JobOrder;
use App\Models\JobOrderAssessment;
use App\Models\JobOrderHasEmployee;
use App\Models\JobOrderHasEmployeeHistory;
use App\Models\JobOrderHasStatus;
use App\Models\JobOrderHistory;
use App\Models\JobStatusHasParent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class JobOrderController extends Controller
{
    private $nameModel = "App\Models\JobOrder";
    private $nameModelJobStatusHasParent = "App\Models\JobStatusHasParent";
    private $nameModelJobOrderHasEmployee = "App\Models\JobOrderHasEmployee";

    public function index()
    {
        $vue = true;
        $baseUrl = Url::to('/');
        $user = auth()->user();
        $statuses = Config::get("library.status");
        $statuses = json_encode($statuses);

        // return $statuses;

        return view("pages.job-order.index", compact("vue", "user", "baseUrl", "statuses"));
    }

    public function fetchData()
    {
        set_time_limit(840); // 14 menit

        $status = request("status");
        $search = request("search");
        $projectId = request("project_id");
        $user = User::find(request("user_id"));
        $month = Carbon::parse(request("month"));

        $jobOrders = JobOrder::with(["jobOrderHasEmployees", "jobOrderAssessments"])
            ->orderBy("created_at", "desc");

        if (request("is_date_filter") == "true") {
            $jobOrders = $jobOrders->whereDate("datetime_start", request("date"));
        } else {
            $jobOrders = $jobOrders->whereYear("datetime_start", $month->format("Y"))
                ->whereMonth("datetime_start", $month->format("m"));
        }

        // jika pengawas, secara default menampilkan datanya berdasarkan dia yang buat
        // terkecuali di filter data pilih dari 'pengawas lain' baru muncul job order dari pengawas lain
        if ($user->group_name == "Pengawas") {
            if (request("created_by") == "creator") {
                $jobOrders = $jobOrders->where("created_by", $user->id);
            } else {
                $jobOrders = $jobOrders->where("created_by", "!=", $user->id);
            }
        } else if ($user->group_name == "Quality Control") {
            $jobOrders = $jobOrders->where("created_by", "!=", $user->id);
        }

        if ($status != "all") {
            $jobOrders = $jobOrders->where("status", $status);
        }

        $listNotProject = ["all", "loading"];

        if (!in_array($projectId, $listNotProject)) {
            $jobOrders = $jobOrders->where("project_id", $projectId);
        }

        if ($search != null) {
            $jobOrders = $jobOrders->where(function ($query) use ($search) {
                $query->orWhereHas("project", function ($queryProject) use ($search) {
                    $queryProject->where("name", "like", "%" . $search . "%");
                })->orWhereHas("job", function ($queryProject) use ($search) {
                    $queryProject->where("name", "like", "%" . $search . "%")
                        ->orWhere("code", "like", "%" . $search . "%");
                })->orWhereHas("creator", function ($queryProject) use ($search) {
                    $queryProject->where("name", "like", "%" . $search . "%");
                });
            });

            $jobOrders = $jobOrders->orWhere("job_note", "like", "%" . $search . "%");
        }

        $jobOrders = $jobOrders->get();

        return response()->json([
            "jobOrders" => $jobOrders,
            "requests" => request()->all(),
        ]);
    }

    public function fetchDataFinish()
    {
        $month = Carbon::parse(request("month"));

        $jobOrders = JobOrder::where("status", "finish")
            ->whereYear("datetime_start", $month->format("Y"))
            ->whereMonth("datetime_start", $month->format("m"))
            ->get();

        return response()->json([
            "jobOrders" => $jobOrders,
            "requests" => request()->all(),
        ]);
    }

    public function findEmployeeStatus()
    {
        $actives = [];
        $result = false;
        $dataSelecteds = request("data_selecteds");

        try {
            if ($dataSelecteds != null) {
                foreach (request("data_selecteds") as $index => $item) {
                    $findData = JobOrderHasEmployee::where([
                        "employee_id" => $item["employee_id"],
                        "datetime_end" => null,
                    ])
                        ->where("status", "!=", "overtime")
                        ->where("status", "!=", "pending")
                        ->where("status", "!=", "finish")
                        ->whereNull("deleted_at")
                        ->where("job_order_id", "!=", request("job_order_id"));

                    $findDataJobStatus = JobStatusHasParent::where([
                        "employee_id" => $item['employee_id'],
                        "job_order_id" => request("job_order_id"),
                    ])
                        ->whereNotNull("datetime_end");

                    if ($findData->count() > 0 && $findDataJobStatus->count() == 0) {
                        $result = true;
                        $getData = $findData
                            ->select("employee_id", "job_order_id", "status")
                            ->first();

                        array_push($actives, $getData);
                    }
                }
            }

            return response()->json([
                "actives" => $actives,
                "request" => request()->all(),
                "result" => $result,
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);


            return response()->json([
                'success' => false,
                'message' => "Gagal cari status karyawan",
            ], 500);
        }
    }

    public function store()
    {
        // return request()->all();

        $imageController = new ImageController;
        $jobStatusController = new JobStatusController;
        $user = User::find(request("user_id"));
        $image = request("image");
        $date = Carbon::now();
        $date = $date->setTimeFromTimeString(request("hour_start"));
        // $date = Carbon::createFromFormat("h:m", request("hour_start"))->format("Y-m-d h:m");

        $getStoreValidation = $this->storeValidation();

        if ($getStoreValidation) {
            return response()->json([
                'success' => false,
                'message' => $this->storeValidation("result"),
            ], 400);
        }

        if (count(request("employee_selecteds")) == 0) {
            return response()->json([
                'success' => false,
                'message' => "Maaf, harus pilih karyawan terlebih dahulu",
            ], 400);
        }

        try {
            DB::beginTransaction();

            if (request("id")) {
                $jobOrder = JobOrder::find(request("id"));

                $message = "diperbaharui";
            } else {
                $jobOrder = new JobOrder;

                $message = "ditambahkan";
                $jobOrder->status = "active";
            }

            $jobOrder->job_id = null;
            $jobOrder->job_another_name = null;

            // kondisi pekerjaan pilih yang "lainnya"
            if (!request("is_not_exists_job")) {
                $jobOrder->job_id = request("job_id");
            } else {
                $jobOrder->job_another_name = request("job_another_name");
            }

            $jobOrder->project_id = request("project_id");
            $jobOrder->job_level = request("job_level");
            $jobOrder->job_note = request("job_note");
            //note: datetime_end inputnya di storeAction
            $jobOrder->datetime_start = $date;
            $jobOrder->datetime_estimation_end = Carbon::parse(request("datetime_estimation_end"));
            $jobOrder->estimation = request("estimation");
            $jobOrder->time_type = request("time_type");
            $jobOrder->category = request("category");
            $jobOrder->note = request("note");
            $jobOrder->save();

            if (request("id") != null) {
                $jobStatusHasParent = $jobStatusController->updateJobStatusHasParent($jobOrder, $this->nameModel);
            } else {
                // tambah data jobOrderHasStatus hanya ketika data baru
                $jobStatusHasParent = $jobStatusController->storeJobStatusHasParent($jobOrder, null, $date, request("is_overtime_rest"), $this->nameModel);
            }

            $this->storeJobOrderHasEmployee($jobOrder, $jobOrder->status, $jobOrder->datetime_start);
            $this->storeJobOrderHistory($jobOrder);

            if ($image != null) {
                $storeImage = $imageController->storeSingle(
                    $user,
                    $image,
                    $jobStatusHasParent->data,
                    $this->nameModelJobStatusHasParent,
                    "job_has_parents",
                    "_active",
                );

                // proses masukkan gambar
                if (!$storeImage->success) {
                    return response()->json([
                        'success' => $storeImage->success,
                        'message' => $storeImage->message,
                    ], $storeImage->code);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'requests' => request()->all(),
                'date' => $date,
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

    public function storeAction()
    {
        // return request()->all();
        $jobStatusController = new JobStatusController;

        $user = User::find(request("user_id"));
        $image = request("image");
        $statusFinish = request("status_finish");
        $statusLast = request("status_last");
        $status = request("status");
        $date = Carbon::parse(request("date") . ' ' . request("hour"))->format("Y-m-d H:i");
        $message = "diperbaharui";

        try {
            DB::beginTransaction();

            $jobOrder = JobOrder::find(request("id"));

            if ($status == 'finish') {
                $jobOrder->status = $status;
                $jobOrder->datetime_end = $date;
            } else {
                $jobOrder->status = $status;
            }

            $jobOrder->status_note = request("status_note");
            $jobOrder->save();

            $this->storeJobOrderHistory($jobOrder);

            $storeActionJobOrderHasEmployee = $this->storeActionJobOrderHasEmployee($jobOrder, $status, $date, $statusLast);
            if (isset($storeActionJobOrderHasEmployee->error) && $storeActionJobOrderHasEmployee->error) {
                return response()->json([
                    'success' => false,
                    'message' => $storeActionJobOrderHasEmployee->message,
                ], 500);
            }

            $jobStatusHasParent = $jobStatusController->storeJobStatusHasParent($jobOrder, $statusLast, $date, request("is_overtime_rest"), $this->nameModel);
            if (isset($jobStatusHasParent->error) && $jobStatusHasParent->error) {
                return response()->json([
                    'success' => false,
                    'message' => $jobStatusHasParent->message,
                ], 500);
            } else {
                $this->storeImage($image, $status, $statusLast, $statusFinish, $user, $jobStatusHasParent->data);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'request' => request()->all(),
                'jobOrder' => $jobOrder,
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

    public function storeActionAssessment()
    {
        // return request()->all();
        $jobStatusController = new JobStatusController;

        $user = User::find(request("user_id"));
        $image = request("image");
        $statusFinish = request("status_finish");
        $statusLast = request("status_last");
        $status = request("status");
        $jobOrderId = request("id");
        $isAssessmentQc = request("is_assessment_qc");
        $jobOrder = JobOrder::find($jobOrderId);
        $date = Carbon::parse(request("date") . ' ' . request("hour"))->format("Y-m-d H:i");

        try {
            DB::beginTransaction();

            $jobOrderAssessment = JobOrderAssessment::updateOrCreate([
                "job_order_id" => $jobOrderId,
                "employee_id" => request("user_id"),
            ], [
                "note" => request("status_note"),
                "date_time" => $date,
            ]);

            $allJobOrderAssessmentHasEmployee = JobOrderAssessment::where([
                "job_order_id" => $jobOrderId,
            ]);

            if ($allJobOrderAssessmentHasEmployee->count() >= 2 || $isAssessmentQc == false) {

                $jobOrder->status = "finish";
                $jobOrder->datetime_end = $date;
                $jobOrder->is_assessment_qc = false;
                $jobOrder->save();

                $this->storeActionJobOrderHasEmployee($jobOrder, "assessment_finish", $date, "active");
                $jobStatusHasParent = $jobStatusController->storeJobStatusHasParent($jobOrder, "active", $date, request("is_overtime_rest"), $this->nameModel);

                $this->storeImage($image, $status, $statusLast, $statusFinish, $user, $jobStatusHasParent->data);
            } else {
                $jobOrder->status = "assessment";
                $jobOrder->save();
            }

            $this->storeJobOrderHistory($jobOrder);

            DB::commit();

            return response()->json([
                'success' => true,
                'request' => request()->all(),
                'message' => "Berhasil Penilaian",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);


            return response()->json([
                'success' => false,
                'message' => "Gagal Penilaian",
            ], 500);
        }
    }

    // perbaharui status karyawan pada job order
    // tampilannya di tombol aksi karyawan di job order
    // di pakai langsung di menu karyawan ubah status per karyawan
    public function storeActionHasEmployee()
    {
        $datetime = Carbon::now();
        $dataEmployees = request("data_employees");
        $jobStatusController = new JobStatusController;

        try {
            DB::beginTransaction();

            foreach ($dataEmployees as $index => $item) {
                // coding backupan. JANGAN DI HAPUS
                // if ($item["status"] == 'pending') {
                //     // $datetime = Carbon::now();
                // } else if (array_key_exists('status_last', $item)) {
                //     if ($item["status_last"] == 'pending') {
                //         // $datetime = Carbon::now();
                //     }
                // } else {
                //     $datetime = Carbon::parse(request("date") . ' ' . request("hour"))->format("Y-m-d H:i");
                // }

                // if (!array_key_exists('status_last', $item)) {
                $jobOrderHasEmployee = JobOrderHasEmployee::find($item["id"]);
                $jobOrderHasEmployee->status = $item["status"];

                if (array_key_exists('status_last', $item)) {
                    $datetime = Carbon::parse(request("date") . ' ' . request("hour"))->format("Y-m-d H:i");

                    $jobOrderHasEmployee->datetime_end = $datetime;
                }

                $jobOrderHasEmployee->save();

                if (array_key_exists('status_last', $item)) {
                    $statusLast = $item["status_last"];

                    $getValidation = $jobStatusController->storeJobStatusHasParent(
                        $jobOrderHasEmployee,
                        $statusLast,
                        $datetime,
                        request("is_overtime_rest"),
                        $this->nameModelJobOrderHasEmployee
                    );

                    if (isset($getValidation->error) && $getValidation->error) {
                        return response()->json([
                            'success' => false,
                            'message' => $getValidation->message,
                        ], 500);
                    }
                }
            }

            DB::commit();

            if ($statusLast == "overtime") {
                $checkExistsEmployeeOvertime = JobOrderHasEmployee::where([
                    "status" => "overtime",
                    "job_order_id" => request("job_order_id"),
                ])->count();

                if ($checkExistsEmployeeOvertime == 0) {
                    $jobOrder = JobOrder::find(request("job_order_id"));
                    $jobOrder->status = "active";
                    $jobOrder->save();

                    $this->storeJobOrderHistory($jobOrder);

                    $getValidation = $jobStatusController->storeJobStatusHasParent(
                        $jobOrder,
                        $statusLast,
                        $datetime,
                        request("is_overtime_rest"),
                        $this->nameModel
                    );

                    if (isset($getValidation->error) && $getValidation->error) {
                        return response()->json([
                            'success' => false,
                            'message' => $getValidation->message,
                        ], 500);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'request' => request()->all(),
                'datetime' => $datetime,
                'message' => "Berhasil memperbaharui status",
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e);

            $routeAction = Route::currentRouteAction();
            $log = new LogController;
            $log->store($e->getMessage(), $routeAction);


            return response()->json([
                'success' => false,
                'message' => "Gagal memperbaharui status",
            ], 500);
        }
    }

    public function destroy()
    {
        $jobStatusController = new JobStatusController;

        try {
            DB::beginTransaction();

            $jobOrder = JobOrder::find(request("id"));
            $jobOrder->update([
                'deleted_by' => request("user_id"),
            ]);
            $this->destroyJobOrderHasEmployee($jobOrder);
            $jobStatusController->destroyJobStatusHasParentBaseJobOrder($jobOrder, $this->nameModel);
            $this->storeJobOrderHistory($jobOrder, true);

            $jobOrder->delete();

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

    private function storeImage($image, $status, $statusLast, $statusFinish, $user, $jobOrderHasParent)
    {
        $imageController = new ImageController;

        if ($image != null) {
            // agar bisa memisahkan folder mulai dan selesai
            if ($statusLast == 'overtime') {
                $addNameFolder = $statusFinish;
            } else {
                $addNameFolder = $status;
            }

            $storeImage = $imageController->storeSingle(
                $user,
                $image,
                $jobOrderHasParent,
                $this->nameModelJobStatusHasParent,
                "job_status_has_parents",
                "_" . $addNameFolder
            );

            // proses masukkan gambar
            if (!$storeImage->success) {
                return response()->json([
                    'success' => $storeImage->success,
                    'message' => $storeImage->message,
                ], $storeImage->code);
            }
        }
    }

    private function storeJobOrderHistory($jobOrder, $isDelete = false)
    {
        $jobOrderHistory = new JobOrderHistory;
        $jobOrderHistory->job_order_id = $jobOrder->id;
        $jobOrderHistory->project_id = $jobOrder->project_id;
        $jobOrderHistory->job_id = $jobOrder->job_id;
        $jobOrderHistory->job_another_name = $jobOrder->job_another_name;
        $jobOrderHistory->job_level = $jobOrder->job_level;
        $jobOrderHistory->job_note = $jobOrder->job_note;
        $jobOrderHistory->status = $jobOrder->status;
        $jobOrderHistory->is_assessment_qc = $jobOrder->is_assessment_qc;
        $jobOrderHistory->datetime_start = $jobOrder->datetime_start;
        $jobOrderHistory->datetime_end = $jobOrder->datetime_end;
        $jobOrderHistory->datetime_estimation_end = $jobOrder->datetime_estimation_end;
        $jobOrderHistory->estimation = $jobOrder->estimation;
        $jobOrderHistory->time_type = $jobOrder->time_type;
        $jobOrderHistory->category = $jobOrder->category;
        $jobOrderHistory->note = $jobOrder->note;
        $jobOrderHistory->status_note = $jobOrder->status_note;
        $jobOrderHistory->deleted_by = $jobOrder->deleted_by;

        if ($isDelete) {
            $jobOrderHistory->deleted_at = Carbon::now();
        }

        $jobOrderHistory->save();
    }

    // di pakai job order tambah dan ubah data
    private function storeJobOrderHasEmployee($jobOrder, $status, $dateStart)
    {
        $jobStatusController = new JobStatusController;

        if (count(request("employee_selecteds")) > 0) {

            foreach (request("employee_selecteds") as $index => $item) {

                if (isset($item["id"])) {
                    $jobOrderHasEmployee = JobOrderHasEmployee::find($item["id"]);
                } else {
                    $jobOrderHasEmployee = new JobOrderHasEmployee;
                }

                if ($item["status_data"] == 'new') {
                    $getDateStart = Carbon::now();
                } else {
                    $getDateStart = $dateStart;
                }

                $jobOrderHasEmployee->project_id = $jobOrder->project_id;
                $jobOrderHasEmployee->job_order_id = $jobOrder->id;
                $jobOrderHasEmployee->employee_id = $item["employee_id"];
                $jobOrderHasEmployee->status = $item["status"];
                $jobOrderHasEmployee->datetime_start = $getDateStart;
                $jobOrderHasEmployee->save();

                if ($item["status_data"] == 'new') {
                    $jobStatusController->storeJobStatusHasParent(
                        $jobOrderHasEmployee,
                        $item["status_last"],
                        $jobOrderHasEmployee->datetime_start,
                        request("is_overtime_rest"),
                        $this->nameModelJobOrderHasEmployee
                    );
                }

                $this->storeJobOrderHasEmployeeHistory($jobOrderHasEmployee);
            }
        }
    }

    // di pakai job order memperbaharui status
    private function storeActionJobOrderHasEmployee($jobOrder, $status, $date, $statusLast)
    {
        $jobStatusController = new JobStatusController;

        if (count(request("employee_selecteds")) > 0) {

            foreach (request("employee_selecteds") as $index => $item) {
                if ($status == 'assessment_finish') {
                    $getStatus = 'finish';
                    $getStatusLast = 'active';
                    $datetimeEnd = $jobOrder->datetime_end;
                } else {
                    $getStatus = $item["status"];
                    $getStatusLast = $item["status_last"];
                    $datetimeEnd = null;
                }

                $jobOrderHasEmployee = JobOrderHasEmployee::find($item["id"]);
                $jobOrderHasEmployee->status = $getStatus;
                $jobOrderHasEmployee->datetime_end = $datetimeEnd;
                $jobOrderHasEmployee->save();

                $this->storeJobOrderHasEmployeeHistory($jobOrderHasEmployee);
                $jobStatusController->storeJobStatusHasParent(
                    $jobOrderHasEmployee,
                    $getStatusLast,
                    $date,
                    request("is_overtime_rest"),
                    $this->nameModelJobOrderHasEmployee
                );
            }
        } else {
            return (object) [
                'error' => true,
                'message' => "Maaf, minimal 1 karyawan",
            ];
        }
    }

    private function storeJobOrderHasEmployeeHistory($jobOrderHasEmployee, $isDelete = false)
    {
        $jobOrderHasEmployeeHistory = new JobOrderHasEmployeeHistory;
        $jobOrderHasEmployeeHistory->project_id = $jobOrderHasEmployee->project_id;
        $jobOrderHasEmployeeHistory->job_order_has_employee_id = $jobOrderHasEmployee->id;
        $jobOrderHasEmployeeHistory->employee_id = $jobOrderHasEmployee->employee_id;
        $jobOrderHasEmployeeHistory->job_order_id = $jobOrderHasEmployee->job_order_id;
        $jobOrderHasEmployeeHistory->status = $jobOrderHasEmployee->status;
        $jobOrderHasEmployeeHistory->datetime_start = $jobOrderHasEmployee->datetime_start;
        $jobOrderHasEmployeeHistory->datetime_end = $jobOrderHasEmployee->datetime_end;

        if ($isDelete) {
            $jobOrderHasEmployeeHistory->deleted_at = Carbon::now();
        }

        $jobOrderHasEmployeeHistory->save();
    }

    public function destroyJobOrderHasEmployee($jobOrder)
    {
        $jobOrderHasEmployee = JobOrderHasEmployee::where([
            "job_order_id" => $jobOrder->id,
        ]);

        $jobOrderHasEmployee->update([
            'deleted_by' => request("user_id"),
        ]);

        foreach ($jobOrderHasEmployee->get() as $index => $item) {
            $getData = JobOrderHasEmployee::find($item->id);

            $this->storeJobOrderHasEmployeeHistory($getData, true);
        }

        $jobOrderHasEmployee->delete();
    }

    private function storeValidation($type = null)
    {
        $isError = false;
        $message = null;

        $lstFormValidations = [
            (object) [
                "field" => "project_id",
                "name" => "Proyek",
            ],
            (object) [
                "field" => "category",
                "name" => "Kategori",
            ],
            (object) [
                "field" => "estimation",
                "name" => "Estimasi Waktu",
            ],
            (object) [
                "field" => "job_level",
                "name" => "Tingkat Kesulitan",
            ],

        ];

        foreach ($lstFormValidations as $index => $item) {
            if (request("{$item->field}") == null) {
                $isError = true;
                $message = "Maaf, {$item->name} harus di masukkan";

                break;
            }
        }

        if ($type == "result") {
            return $message;
        }

        return $isError;
    }
}
