<?php

namespace App\Console\Commands;

use App\Http\Controllers\PayslipController;
use App\Http\Controllers\PeriodPayrollController;
use App\Models\Employee;
use App\Models\PeriodPayroll;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;


class GenerateGajiCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate_gaji:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        


        $date_now = Carbon::now();
        // $date_now->format('Y-m');
        // $date_now->startOfMonth()->format('Y-m') . "-25";
        // $date_now->startOfMonth()->addMonths(-1)->format('Y-m') . "-26";

        // // $periode = "";
        // // $date_start = "";
        // // $date_end = "";

        // // $data_period_payroll = (object) [
        // //     "periode" => $date_now->format('Y-m'),
        // //     "date_end" => 
        // //     "date_start" => ,

        // // ];


        $bulan = $date_now->format('m');
        $tahun = $date_now->format('Y');

        $employees = Employee::where('employee_status','aktif')->get();
        // print($employees->count());exit;

       



        $paySlipController = new PayslipController();

        foreach ($employees as $key => $e) {
            $request = new Request(
                [
                  'bulan' => $bulan,
                  'tahun' => $tahun,
                  'employee_id'=>$e->id
    
                ],
                [],
                [], 
                [],
                [],
                ['CONTENT_TYPE' => 'application/json']
              );

              $response = $paySlipController->store($request);

              print("EMPLOYE :: ".$e->id."\n");

        }
        

        // PeriodPayroll
        $period_payroll = PeriodPayroll::whereMonth('period', $bulan)->whereYear('period', $tahun)->first();

        $periodPayrollController =  new PeriodPayrollController();
        $periodPayrollController->store($period_payroll,$employees);

        return 0;
    }
}
