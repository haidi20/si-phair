<?php

namespace App\Http\Controllers;

use App\Exports\ProjectExport;
use App\Models\Contractor;
use App\Models\ContractorHasParent;
use App\Models\JobOrder;
use App\Models\OrdinarySeamanHasParent;
use App\Models\Project;
use App\Models\ProjectHistory;
use App\Models\ProjectSimple;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

class ProjectController extends Controller
{
    public function index()
    {
        $vue = true;
        $baseUrl = Url::to('/');
        $user = auth()->user();

        return view("pages.project.index", compact("vue", "user", "baseUrl"));
    }

    public function fetchData()
    {
        $month = Carbon::parse(request("month"));
        $monthReadAble = $month->isoFormat("MMMM YYYY");

        // $projects = Project::with(["contractors", "ordinarySeamans", "jobOrders"])
        // ->whereYear("created_at", $month->format("Y"))
        // ->whereMonth("created_at", $month->format("m"))
        // ->orderBy("created_at", "desc")->get();

        $projects = ProjectSimple::orderBy("created_at", "desc")->get();

        return response()->json([
            "projects" => $projects,
        ]);
    }

    public function fetchDataRelation()
    {
        $contractors = ContractorHasParent::where("parent_model", "App\Models\Project")
            ->where("parent_id", request("project_id"))
            ->get();
        $ordinarySeamans = OrdinarySeamanHasParent::where("parent_model", "App\Models\Project")
            ->where("parent_id", request("project_id"))
            ->get();

        return response()->json([
            "requests" => request()->all(),
            "contractors" => $contractors,
            "ordinarySeamans" => $ordinarySeamans,
        ]);
    }

    public function fetchDataBaseJobOrderFinish()
    {
        $month = Carbon::parse(request("month"));

        // $projects = Project::jobOrderFinish()
        //     ->whereYear("created_at", $month->format("Y"))
        //     ->whereMonth("created_at", $month->format("m"))
        //     ->orderBy("created_at", "asc")->get();
        $projects = ProjectSimple::select('id', 'name')->orderBy('created_at')->get();

        return response()->json([
            "month" => $month->format("m"),
            "projects" => $projects,
        ]);
    }

    public function fetchDataBaseDateEnd()
    {
        $user = null;
        $date = Carbon::now();

        if (request("user_id")) {
            $user = User::find(request("user_id"));
        }

        // $projects = Project::with(["contractors", "ordinarySeamans", "jobOrders"]);
        $projects = ProjectSimple::select("id", "name", "location_id");
        // ->whereDate("date_end", ">=", $date);

        // data proyek berdasarkan lokasi pengguna,
        // jadi jika pengawas tersebut ada di DOC 2, maka yg muncul proyek di DOC 2
        if ($user != null) {
            $locationId = $user->location_id ? $user->location_id : null;

            if ($locationId != null) {
                $projects = $projects->where("location_id", $locationId);
            }
        }

        // $projects = $projects->orderBy("date_end", "asc")->get();
        $projects = $projects->get();

        return response()->json([
            "projects" => $projects,
        ]);
    }

    public function fetchDataBaseRunning()
    {
        set_time_limit(840); // 14 menit

        $month = Carbon::parse(request("month"));

        $getProjectIdFromJobOrder = JobOrder::whereNull("datetime_end")
            ->whereYear("datetime_start", $month->format("Y"))
            ->whereMonth("datetime_start", $month->format("m"))
            ->pluck("project_id");

        $projects = ProjectSimple::select("id", "name")->whereIn("id", $getProjectIdFromJobOrder)->get();

        return response()->json([
            "projects" => $projects,
        ]);
    }


    public function export()
    {
        $data = $this->fetchData()->original["projects"];
        $month = Carbon::parse(request("month"));
        $monthReadAble = $month->isoFormat("MMMM YYYY");
        $dateRange = $this->dateRange($month->format("Y-m"));
        $nameFile = "export/project_{$monthReadAble}.xlsx";

        try {
            Excel::store(new ProjectExport($data), $nameFile, 'real_public', \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                "success" => true,
                "data" => $data,
                "linkDownload" => route('project.download', ["path" => $nameFile]),
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal export data',
            ], 500);
        }
    }

    public function download()
    {
        $path = public_path(request("path"));

        return Response::download($path);
    }

    public function store()
    {
        // return request()->all();

        $checkDuplicateContractor = $this->detectDuplicateData(request("contractors"), ['contractor_id']);

        if ($checkDuplicateContractor) {
            return response()->json([
                'success' => false,
                'checkDuplicateContractor' => $checkDuplicateContractor,
                'message' => "Maaf, Data Kepala Pemborong tidak boleh sama",
            ], 401);
        }

        $checkDuplicateOs = $this->detectDuplicateData(request("ordinary_seamans"), ['ordinary_seaman_id']);

        if ($checkDuplicateOs) {
            return response()->json([
                'success' => false,
                'checkDuplicateOs' => $checkDuplicateOs,
                'message' => "Maaf, Data OS tidak boleh sama",
            ], 401);
        }

        $getStoreValidation = $this->storeValidation();

        if ($getStoreValidation) {
            return response()->json([
                'success' => false,
                'message' => $this->storeValidation("message"),
            ], 400);
        }

        try {
            DB::beginTransaction();

            if (request("id")) {
                $project = Project::find(request("id"));

                $message = "diperbaharui";
            } else {
                $project = new Project;

                $message = "ditambahkan";
            }

            $dateStart = null;
            $dateEnd = null;
            if (request("date_end") != null) {
                $dateStart = Carbon::parse(request("date_start"))->format("Y-m-d");
                $dateEnd = Carbon::parse(request("date_end"))->format("Y-m-d");
            }

            // $project->company_id = request("company_id");
            $project->foreman_id = request("foreman_id");
            $project->barge_id = request("barge_id");
            $project->location_id = request("location_id");
            $project->name = request("name");
            $project->date_start = $dateStart;
            $project->date_end = $dateEnd;
            $project->day_duration = request("day_duration");
            $project->price = request("price");
            $project->down_payment = request("down_payment");
            $project->remaining_payment = request("remaining_payment");
            $project->type = request("type");
            $project->note = request("note");
            $project->save();

            $this->storeContractors($project);
            $this->storeOrdinarySeamans($project);
            $this->storeProjectHistories($project);


            DB::commit();

            return response()->json([
                'success' => true,
                'requests' => request()->all(),
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

    public function destroy()
    {
        try {
            DB::beginTransaction();

            $project = Project::find(request("id"));
            $project->update([
                'deleted_by' => request("user_id"),
            ]);
            $project->delete();

            $this->storeProjectHistories($project, true);

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

    private function storeProjectHistories($project, $isDelete = false)
    {
        $projectHistory = new ProjectHistory;
        $projectHistory->project_id = $project->id;
        $projectHistory->foreman_id = $project->foreman_id;
        $projectHistory->barge_id = $project->barge_id;
        $projectHistory->name = $project->name;
        $projectHistory->date_start = $project->date_start;
        $projectHistory->date_end = $project->date_end;
        $projectHistory->day_duration = $project->day_duration;
        $projectHistory->price = $project->price;
        $projectHistory->down_payment = $project->down_payment;
        $projectHistory->remaining_payment = $project->remaining_payment;
        $projectHistory->type = $project->type;
        $projectHistory->note = $project->note;
        $projectHistory->created_by = $project->created_by;
        $projectHistory->updated_by = $project->updated_by;
        $projectHistory->created_at = $project->created_at;
        $projectHistory->updated_at = $project->updated_at;
        if ($isDelete) {
            $projectHistory->deleted_by = $project->deleted_by;
            $projectHistory->deleted_at = Carbon::now();
        }
        $projectHistory->save();
    }

    private function storeContractors($project)
    {
        $getData = [
            "parent_id" => $project->id,
            "parent_model" => "App\Models\Project",
        ];

        $contractorHasParentDelete = ContractorHasParent::where($getData);
        $contractorHasParentDelete->delete();

        if (request("contractors") != null) {

            foreach (request("contractors") as $index => $item) {
                $getData["contractor_id"] =  $item["contractor_id"];
                ContractorHasParent::create($getData);
            }
        }
    }

    private function storeOrdinarySeamans($project)
    {
        $getData = [
            "parent_id" => $project->id,
            "parent_model" => "App\Models\Project",
        ];

        $oridnarySeamanHasParentDelete = OrdinarySeamanHasParent::where($getData);
        $oridnarySeamanHasParentDelete->delete();

        if (request("ordinary_seamans") != null) {

            foreach (request("ordinary_seamans") as $index => $item) {
                $getData["ordinary_seaman_id"] =  $item["ordinary_seaman_id"];
                OrdinarySeamanHasParent::create($getData);
            }
        }
    }

    private function detectDuplicateData($array, $properties)
    {
        $uniqueData = [];
        $duplicates = [];

        if ($array) {
            foreach ($array as $item) {
                $data = '';
                foreach ($properties as $property) {
                    $data .= $item[$property];
                }

                if (in_array($data, $uniqueData)) {
                    $duplicates[] = $item;
                } else {
                    $uniqueData[] = $data;
                }
            }
        }

        return $duplicates;
    }

    private function storeValidation($type = null)
    {
        $isError = false;
        $message = null;

        $lstFormValidations = [
            (object) [
                "field" => "name",
                "name" => "Nama proyek",
            ],
            (object) [
                "field" => "location_id",
                "name" => "Lokasi",
            ],

        ];

        foreach ($lstFormValidations as $index => $item) {
            if (request("{$item->field}") == null) {
                $isError = true;
                $message = "Maaf, {$item->name} harus di masukkan";

                break;
            }
        }

        if (request("date_start") != null && request("date_end") != null) {
            $start = Carbon::parse(request("date_start"));
            $end = Carbon::parse(request("date_end"));

            if ($end->lessThan($start)) {
                $isError = true;
                $message = "Maaf, tanggal selesai tidak boleh sebelum tanggal selesai harus di masukkan";
            }
        }

        if ($type == "message") {
            return $message;
        }

        return $isError;
    }

    private function fetchDataOld()
    {
        $projects = [
            (object)[
                "id" => 1,
                "name" => "Pengerjaan Kapal A",
                "barge_name" => "Kapal A",
                "company_name" => "PT. Maju Jaya",
                "job_order_total" => 4,
                "job_order_total_finish" => 5,
            ]
        ];

        return response()->json([
            "projects" => $projects,
        ]);
    }
}
