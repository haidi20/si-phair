<?php

use App\Http\Controllers\ApprovalAgreementController;
use App\Http\Controllers\ApprovalLevelController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BargeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FingerToolController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\JobOrderReportController;
use App\Http\Controllers\JobStatusController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\OrdinarySeamanController;
use App\Http\Controllers\OvertimeReportController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\RosterStatusController;
use App\Http\Controllers\SalaryAdjustmentController;
use App\Http\Controllers\SalaryAdvanceController;
use App\Http\Controllers\SalaryAdvanceReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\VacationReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('fetch-data-main', [AttendanceController::class, "fetchDataMain"])->name('fetchDataMain');
