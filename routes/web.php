<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\ApprovalLevelController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BargeController;
use App\Http\Controllers\BaseWagesBpjsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmenController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeTypeController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobOrderCategoryController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\JobOrderReportController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\OvertimeReportController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PayslipController;


use App\Http\Controllers\TanggalMerahController;
use App\Http\Controllers\PeriodPayrollController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\RosterController;
use App\Http\Controllers\SalaryAdjustmentController;
use App\Http\Controllers\SalaryAdvanceController;
use App\Http\Controllers\SalaryAdvanceReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\WorkingHourController;
use App\Http\Controllers\FingerToolController;
use App\Http\Controllers\BpjsCalculationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobStatusController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\VacationReportController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

// Guest-only routes
Route::group(['middleware' => 'guest'], function () {
    // Route::get('/', 'Auth\RegisterController@showRegistrationForm');
    Route::get('/', [LoginController::class, "showLoginForm"]);
});

//
Route::group(['middleware' => 'auth'], function () {
    // Route::get('/', 'Auth\RegisterController@showRegistrationForm');
    Route::prefix("dashboard")->name("dashboard.")->group(function () {
        Route::get('', [DashboardController::class, "index"])->name("index");
        Route::get('export', [DashboardController::class, "export"])->name("export");
        Route::get('download', [DashboardController::class, "download"])->name("download");
        Route::get('print', [DashboardController::class, "print"])->name("print");
    });
    Route::prefix("attendance")->name("attendance.")->group(function () {
        Route::get('', [AttendanceController::class, "index"])->name("index");
        Route::get('export', [AttendanceController::class, "export"])->name("export");
        Route::get('download', [AttendanceController::class, "download"])->name("download");
        Route::get('print', [AttendanceController::class, "print"])->name("print");
    });
    Route::prefix("roster")->name("roster.")->group(function () {
        Route::get('', [RosterController::class, "index"])->name("index");
        Route::get('export', [RosterController::class, "export"])->name("export");
        Route::get('download', [RosterController::class, "download"])->name("download");
    });
    // salary advance = kasbon
    Route::prefix("salary-advance")->name("salaryAdvance.")->group(function () {
        Route::get('', [SalaryAdvanceController::class, "index"])->name("index");
    });
    // salary adjustment = penyesuaian gaji
    Route::prefix('salary-adjustment')->name("salaryAdjustment.")->group(function () {
        Route::get('', [SalaryAdjustmentController::class, "index"])->name("index");
    });
    Route::prefix("overtime")->name("overtime.")->group(function () {
        Route::get('', [OvertimeController::class, "index"])->name("index");
    });
    Route::prefix("payslip")->name("payslip.")->group(function () {
        Route::get('', [PayslipController::class, "index"])->name("index");
        Route::post('', [PayslipController::class, "store"])->name("store");

        Route::get('download_slip', [PayslipController::class, "store"])->name("store");
    });

    Route::prefix("period_payroll")->name("period_payroll.")->group(function () {
        Route::get('', [PeriodPayrollController::class, "index"])->name("index");
        Route::get('export', [PeriodPayrollController::class, "export"])->name("export");
        Route::post('', [PeriodPayrollController::class, "store"])->name("store");
        Route::delete('delete', [PeriodPayrollController::class, "destroy"])->name("delete");
    });

    Route::prefix("tanggal_merah")->name("tanggal_merah.")->group(function () {
        Route::get('', [TanggalMerahController::class, "index"])->name("index");
        Route::post('', [TanggalMerahController::class, "store"])->name("store");
        // Route::get('/{id}/edit_attendance', [PayrollController::class, "edit_attendance"])->name("edit_attendance");
        // Route::put('/{id}/update_attendance', [PayrollController::class, "update_attendance"])->name("update_attendance");
    });




    Route::prefix("payroll")->name("payroll.")->group(function () {
        Route::get('', [PayrollController::class, "monthly"])->name("monthly");
        Route::get('/{id}/edit_attendance', [PayrollController::class, "edit_attendance"])->name("edit_attendance");
        Route::put('/{id}/update_attendance', [PayrollController::class, "update_attendance"])->name("update_attendance");
    });
    Route::prefix("project")->name("project.")->group(function () {
        Route::get('', [ProjectController::class, "index"])->name("index");
        Route::get('export', [ProjectController::class, "export"])->name("export");
        Route::get('download', [ProjectController::class, "download"])->name("download");
    });
    Route::prefix("job-order")->name("jobOrder.")->group(function () {
        Route::get('', [JobOrderController::class, "index"])->name("index");
    });
    Route::prefix("job-status-has-parent")->name("jobOrder.")->group(function () {
        Route::get('download-image', [JobStatusController::class, "downloadImage"])->name("downloadImage");
    });
    // vacation = cuti kerja
    Route::prefix("vacation")->name("vacation.")->group(function () {
        Route::get('', [VacationController::class, "index"])->name("index");
    });

    Route::prefix("report")->name("report.")->group(function () {
        Route::prefix("job-order")->name("jobOrder.")->group(function () {
            Route::get('', [JobOrderReportController::class, "index"])->name("index");
            Route::get('print', [JobOrderReportController::class, "print"])->name("print");
            Route::get('export', [JobOrderReportController::class, "export"])->name("export");
            Route::get('download', [JobOrderReportController::class, "download"])->name("download");
        });
        Route::prefix("salary-advance")->name("salaryAdvance.")->group(function () {
            Route::get('', [SalaryAdvanceReportController::class, "index"])->name("index");
            Route::get('export', [SalaryAdvanceReportController::class, "export"])->name("export");
            Route::get('download', [SalaryAdvanceReportController::class, "download"])->name("download");
        });
        Route::prefix("overtime")->name("overtime.")->group(function () {
            Route::get('', [OvertimeReportController::class, "index"])->name("index");
            Route::get('export', [OvertimeReportController::class, "export"])->name("export");
            Route::get('download', [OvertimeReportController::class, "download"])->name("download");
        });
        Route::prefix("vacation")->name("vacation.")->group(function () {
            Route::get('', [VacationReportController::class, "index"])->name("index");
            Route::get('export', [VacationReportController::class, "export"])->name("export");
            Route::get('download', [VacationReportController::class, "download"])->name("download");
        });
    });

    Route::prefix("master")->name("master.")->group(function () {
        Route::prefix('company')->name("company.")->group(function () {
            Route::get('', [CompanyController::class, "index"])->name("index");
            Route::post('store', [CompanyController::class, "store"])->name("store");
            Route::delete('delete', [CompanyController::class, "destroy"])->name("delete");
        });
        Route::prefix('employee-type')->name("employeeType.")->group(function () {
            Route::get('', [EmployeeTypeController::class, "index"])->name("index");
            Route::post('store', [EmployeeTypeController::class, "store"])->name("store");
            Route::delete('delete', [EmployeeTypeController::class, "destroy"])->name("delete");
        });
        // barge = kapal tongkang
        Route::prefix('barge')->name("barge.")->group(function () {
            Route::get('', [BargeController::class, "index"])->name("index");
            Route::post('store', [BargeController::class, "store"])->name("store");
            Route::delete('delete', [BargeController::class, "destroy"])->name("delete");
        });
        Route::prefix('job')->name("job.")->group(function () {
            Route::get('', [JobController::class, "index"])->name("index");
            Route::post('store', [JobController::class, "store"])->name("store");
            Route::delete('delete', [JobController::class, "destroy"])->name("delete");
        });
        Route::prefix('departmen')->name("departmen.")->group(function () {
            Route::get('', [DepartmenController::class, "index"])->name("index");
            Route::get('get-last-code', [DepartmenController::class, "getLastCode"])->name("getLastCode");
            Route::post('store', [DepartmenController::class, "store"])->name("store");
            Route::delete('delete', [DepartmenController::class, "destroy"])->name("delete");
        });
        Route::prefix('position')->name("position.")->group(function () {
            Route::get('', [PositionController::class, "index"])->name("index");
            Route::post('store', [PositionController::class, "store"])->name("store");
            Route::delete('delete', [PositionController::class, "destroy"])->name("delete");
        });
        Route::prefix('location')->name("location.")->group(function () {
            Route::get('', [LocationController::class, "index"])->name("index");
            Route::post('store', [LocationController::class, "store"])->name("store");
            Route::delete('delete', [LocationController::class, "destroy"])->name("delete");
        });
        Route::prefix('finger-tool')->name("fingerTool.")->group(function () {
            Route::get('', [FingerToolController::class, "index"])->name("index");
            Route::post('store', [FingerToolController::class, "store"])->name("store");
            Route::delete('delete', [FingerToolController::class, "destroy"])->name("delete");
        });
        Route::prefix('customer')->name("customer.")->group(function () {
            Route::get('', [CustomerController::class, "index"])->name("index");
            Route::get('get-last-code', [CustomerController::class, "getLastCode"])->name("getLastCode");
            Route::post('store', [CustomerController::class, "store"])->name("store");
            Route::delete('delete', [CustomerController::class, "destroy"])->name("delete");
        });
        Route::prefix('material')->name("material.")->group(function () {
            Route::get('', [MaterialController::class, "index"])->name("index");
        });
        Route::prefix("job-order-category")->name("jobOrderCategory.")->group(function () {
            Route::get('', [JobOrderCategoryController::class, "index"])->name("index");
        });
        Route::prefix("schedule")->name("schedule.")->group(function () {
            Route::get('', [ScheduleController::class, "index"])->name("index");
        });
        Route::prefix('employee')->name("employee.")->group(function () {
            Route::get('', [EmployeeController::class, "index"])->name("index");
            Route::get('get-departmens/{companyId}', [DepartmensController::class, "getDepartmens"])->name("getDepartmen");
            Route::get('get-positions/{departmenId}', [PositionsController::class, "getPositions"])->name("getPosition");
            Route::get('get-fingers/{employeeId}', [EmployeeController::class, "getEmployeeFingers"])->name("getEmployeeFingers");
            Route::delete('delete-fingers/{employeeId}', [EmployeeController::class, "deleteEmployeeFingers"])->name("deleteEmployeeFingers");
            Route::get('exportExcelPosition/{position_id}', [EmployeeController::class, 'exportExcelPositionEmployee'])->name('exportExcelPosition');
            Route::get('exportExcelLocation/{location_id}', [EmployeeController::class, 'exportExcelLocationEmployee'])->name('exportExcelLocation');
            Route::get('exportExcelCompany/{company_id}', [EmployeeController::class, 'exportExcelCompanyEmployee'])->name('exportExcelCompany');
            Route::post('bpjs-jht', [EmployeeController::class, "bpjsJHT"])->name("bpjsJHT");
            Route::post('bpjs-jkk', [EmployeeController::class, "bpjsJKK"])->name("bpjsJKK");
            Route::post('bpjs-jkm', [EmployeeController::class, "bpjsJKM"])->name("bpjsJKM");
            Route::post('bpjs-jp', [EmployeeController::class, "bpjsJP"])->name("bpjsJP");
            Route::post('bpjs-kes', [EmployeeController::class, "bpjsKES"])->name("bpjsKES");
            Route::post('store', [EmployeeController::class, "store"])->name("store");
            Route::delete('delete', [EmployeeController::class, "destroy"])->name("delete");
            Route::get('export', [EmployeeController::class, "export"])->name("export");
            Route::get('download', [EmployeeController::class, "download"])->name("download");
        });
        Route::prefix('working-hour')->name("workingHour.")->group(function () {
            Route::get('', [WorkingHourController::class, "index"])->name("index");
            Route::post('store', [WorkingHourController::class, "store"])->name("store");
        });
        Route::prefix('finger')->name("finger.")->group(function () {
            Route::get('', [FingerController::class, "index"])->name("index");
            Route::post('store', [FingerController::class, "finger"])->name("store");
            Route::delete('delete', [FingerController::class, "destroy"])->name("delete");
        });
    });

    Route::prefix("setting")->name("setting.")->group(function () {
        Route::prefix('approval-level')->name("approvalLevel.")->group(function () {
            Route::get('', [ApprovalLevelController::class, "index"])->name("index");
            Route::post("store", [ApprovalLevelController::class, 'store'])->name("store");
        });
        Route::prefix('user')->name("user.")->group(function () {
            Route::get('', [UserController::class, "index"])->name("index");
            Route::post('store', [UserController::class, "store"])->name("store");
            Route::delete('delete', [UserController::class, "destroy"])->name("delete");
        });
        Route::prefix('role')->name("role.")->group(function () {
            Route::get('', [RoleController::class, "index"])->name("index");
            Route::post('store', [RoleController::class, "store"])->name("store");
            Route::delete('delete', [RoleController::class, "destroy"])->name("delete");
        });
        Route::prefix('role-permission/{roleId}')->name("rolePermission.")->group(function () {
            Route::get('', [RolePermissionController::class, "index"])->name("index");
            Route::get('show', [RolePermissionController::class, "show"])->name("show");
            Route::post('store', [RolePermissionController::class, "store"])->name("store");
        });
        Route::prefix('feature')->name("feature.")->group(function () {
            Route::get('', [FeatureController::class, "index"])->name("index");
            Route::post('store', [FeatureController::class, "store"])->name("store");
            Route::delete('delete', [FeatureController::class, "destroy"])->name("delete");
        });
        Route::prefix('bpjs-calculation')->name("bpjsCalculation.")->group(function () {
            Route::get('', [BpjsCalculationController::class, "index"])->name("index");
            Route::post('get-base-wages', [BpjsCalculationController::class, "getBaseWages"])->name("get-base-wages");
            Route::post('store', [BpjsCalculationController::class, "store"])->name("store");
            Route::delete('delete', [BpjsCalculationController::class, "destroy"])->name("delete");
        });
        Route::prefix('base-wages-bpjs')->name("baseWagesBpjs.")->group(function () {
            Route::get('', [BaseWagesBpjsController::class, "index"])->name("index");
            Route::post('store', [BaseWagesBpjsController::class, "store"])->name("store");
            Route::delete('delete', [BaseWagesBpjsController::class, "destroy"])->name("delete");
        });
        Route::prefix("permission")->name("permission.")->group(function () {
            Route::get('{featureId}', [PermissionController::class, "index"])->name("index");
            Route::post('store', [PermissionController::class, "store"])->name("store");
            Route::delete('delete', [PermissionController::class, "destroy"])->name("delete");
        });
        Route::prefix("log")->name("log.")->group(function () {
            Route::get('', [LogController::class, "index"])->name("index");
        });
    });
});


Route::get('/home', [HomeController::class, 'index'])->name('home');

Auth::routes();
