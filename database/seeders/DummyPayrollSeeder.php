<?php

namespace Database\Seeders;

use App\Http\Controllers\PeriodPayrollController;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\JobOrder;
use App\Models\JobOrderHasEmployee;
use App\Models\JobStatusHasParent;
use App\Models\Payroll;
use App\Models\PeriodPayroll;
// use App\Models\JobStatusHasParent;
use App\Models\Position;
use App\Models\Roster;
use App\Models\RosterDaily;
use Illuminate\Database\Seeder;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
// php artisan db:seed --class=DummyPayrollSeeder
class DummyPayrollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        ///////////////////////////////////////////////////////////////////////////

        //rusli nur
        Karyawan::where('id','40')->update(['basic_salary'=>20000000,'allowance'=>0,'meal_allowance_per_attend'=>0]);

        //evie
        Karyawan::where('id','44')->update(['basic_salary'=>3394000,'allowance'=>500000,'meal_allowance_per_attend'=>12000]);

        //evie
        Karyawan::where('id','44')->update(['basic_salary'=>10000000,'allowance'=>0,'meal_allowance_per_attend'=>0]);


        ///////////////////////////////////////////////////////////////////////////
        // $employees = Employee::where('id',1)->with('position')->get();

        $employees = Employee::with('position')->get();

        Employee::where('id',1)->update([
            'basic_salary'=>15000000,
            'ptkp'=>'K/0'
        ]);

        RosterDaily::truncate();
        Attendance::truncate();

        JobOrder::truncate();
        JobOrderHasEmployee::truncate();
        JobStatusHasParent::truncate();
        PeriodPayroll::truncate();
        Payroll::truncate();




        $is_lembur = 0;

        $karyawan_tipe_1 = 0;
        $karyawan_tipe_2 = 0;
        $karyawan_tipe_3 = 0;
        $tipe_karyawan  = '';


        Position::whereIn('name', [
            'Head of Acounting',
            'Acounting',
            'Purchasing',
            'Cashier',
            'Admin',
            'Admin A/R',
            'Marketing',
            'office Girl',
            'Head of Warehouse',
            'Adm. Warehouse'
        ])->update(['is_office' => 1]);

        $iter = 0;

        foreach ($employees as $key => $e) {

            if (isset($e->position->is_office) && ($e->position->is_office == 1)) {
                $e->working_hour = '5,2';
                $e->save();
                $karyawan_tipe_1++;
                $tipe_karyawan = "1";
            } else {

                // $iter = 1;

                if ($iter % 2 == 0) {
                    $e->working_hour = '6,1';
                    $karyawan_tipe_2++;
                    $tipe_karyawan = "2";
                } else {
                    $e->working_hour = '5,2';
                    $karyawan_tipe_3++;
                    $tipe_karyawan = "3";
                }

                $e->save();
            }

            if ($tipe_karyawan == 1 && $karyawan_tipe_1 > 4) {
                continue;
            }

            if ($tipe_karyawan == 1 && $karyawan_tipe_1 == 2) {
                $e->update([
                    'enter_date' => '2022-06-01'
                ]); // continue;
            }



            if ($tipe_karyawan == 2 && $karyawan_tipe_2 > 4) {
                continue;
            }

            if ($tipe_karyawan == 2 && $karyawan_tipe_2 == 2) {
                $e->update([
                    'enter_date' => '2022-06-15'
                ]); // continue;
            }

            if ($tipe_karyawan == 3 && $karyawan_tipe_3 > 4) {
                continue;
            }

            if ($tipe_karyawan == 3 && $karyawan_tipe_3 == 2) {
                $e->update([
                    'enter_date' => '2022-06-20'
                ]); // continue;
            }



            // if($karyawan_tipe_1 >  3 || $karyawan_tipe_2 >  3 || $karyawan_tipe_3 >  3 ){
            //     print("karyawan_tipe_1 -> ".$karyawan_tipe_1." | karyawan_2 -> ".$karyawan_tipe_2." | karyawan_3 -> ".$karyawan_tipe_3."\n");
            //     continue;
            // }

            $period = CarbonPeriod::create('2021-12-26', '2022-12-25');
            // print("GENERATE EMLOYEE_ID : ".$e->id." | Tipe Karyawan : ".$tipe_karyawan."\n");

            foreach ($period as $key => $p) {


                $name_day = strtolower($p->format('l'));

                if ($name_day == 'saturday' and $e->working_hour  == '5,2') {
                    $status_roster = 'OFF';
                    $status_roster_id = 5;
                    // print("\t\t---------------------\n");
                } elseif ($name_day == 'sunday' and $e->working_hour  == '5,2') {
                    $status_roster = 'OFF';
                    $status_roster_id = 5;
                    // print("\t\t---------------------\n");
                } elseif ($name_day == 'sunday' and $e->working_hour  == '6,1') {
                    $status_roster = 'OFF';
                    $status_roster_id = 5;
                    // print("\t\t---------------------\n");
                } else {
                    $status_roster = 'M';
                    $status_roster_id = 3;
                }


                print("GENERATE EMLOYEE_ID : " . $e->id . " ||  PERIOD : " . $p->format('d F Y') . " | ROSTER : " . $status_roster . " | WORKING ->" . $e->working_hour . "\n");



                // print($name_day."\n");
                $roster = RosterDaily::firstOrcreate([
                    'date' => $p,
                    'employee_id' => $e->id,
                    'position_id' => $e->position->id ?? null,
                    // 'month'=>$p->startOfMonth(),
                    'roster_status_id' => $status_roster_id
                ]);



                $hour_overtime_start = null;
                $hour_overtime_end = null;
                $duration_overtime = null;

                if ($e->working_hour == '6,1') {
                    if ($status_roster_id == '5') {
                        $hour_start = null;
                        $hour_end = null;
                        $duration_work = 0;
                        // $status_roster_id = 0;
                    } else {
                        if ($name_day == 'saturday') {
                            $hour_start = $p->format('Y-m-d') . " 09:00:00";
                            $hour_end = $p->format('Y-m-d') . " 14:00:00";
                            $duration_work = 4;
                        } else {
                            $hour_start = $p->format('Y-m-d') . " 09:00:00";
                            $hour_end = $p->format('Y-m-d') . " 17:00:00";
                            $duration_work = 7;

                            $duration_overtime = \mt_rand(0, 15);

                            if ($duration_overtime > 0) {

                                $hour_overtime = Carbon::parse($p->format('Y-m-d') . " 17:00:59");
                                $hour_overtime_start = $hour_overtime->format('Y-m-d H:i:s');
                                $hour_overtime_end = $hour_overtime->addHours($duration_overtime)->format('Y-m-d H:i:s');

                                $job_order = JobOrder::create([
                                    'project_id' => 1,
                                    'job_id' => 1,
                                    'job_level' => 'middle',
                                    'job_note' => 'TES PAYROLL',
                                    'datetime_start' => $hour_overtime_start,
                                    'datetime_end' => $hour_overtime_end,
                                    'datetime_estimation_end' => $hour_overtime_end,
                                    'estimation' => $duration_overtime * 60,
                                    'time_type' => 'minutes',
                                    'category' => 'reguler',
                                    'status' => 'overtime',
                                    'status_note' => 'TEST PAYROLL',
                                    'note' => 'TEST PAYROLL',


                                ]);

                                $job_order_has_employee = JobOrderHasEmployee::create([
                                    'employee_id' => $e->id,
                                    'project_id' => $job_order->project_id,
                                    'job_order_id' => $job_order->id,
                                ]);

                                $job_status_has_parent = JobStatusHasParent::create([
                                    'parent_id' => $job_order_has_employee->id,
                                    'parent_model' => 'App\\Models\\JobOrderHasEmployee',
                                    'employee_id' => $job_order_has_employee->employee_id,
                                    'job_order_id' => $job_order->id,
                                    'status' => 'overtime',
                                    'datetime_start' => $hour_overtime_start,
                                    'datetime_end' => $hour_overtime_end,
                                    'note_end' => 'TES PAYROLL'


                                ]);
                            }
                        }
                    }
                } else {
                    if ($status_roster_id == '5') {
                        $hour_start = null;
                        $hour_end = null;
                        $duration_work = 0;
                        // $status_roster_id = 0;
                    } else {
                        $hour_start = $p->format('Y-m-d') . " 08:00:00";
                        $hour_end = $p->format('Y-m-d') . " 17:00:00";
                        $duration_work = 8;

                        $hour_overtime = Carbon::parse($p->format('Y-m-d') . " 17:00:59");

                        $duration_overtime = \mt_rand(0, 15);

                        if ($duration_overtime > 0) {
                            $hour_overtime = Carbon::parse($p->format('Y-m-d') . " 17:00:59");
                            $hour_overtime_start = $hour_overtime->format('Y-m-d H:i:s');
                            $hour_overtime_end = $hour_overtime->addHours($duration_overtime)->format('Y-m-d H:i:s');


                            $job_order = JobOrder::create([
                                'project_id' => 1,
                                'job_id' => 1,
                                'job_level' => 'middle',
                                'job_note' => 'TES PAYROLL',
                                'datetime_start' => $hour_overtime_start,
                                'datetime_end' => $hour_overtime_end,
                                'datetime_estimation_end' => $hour_overtime_end,
                                'estimation' => $duration_overtime * 60,
                                'time_type' => 'minutes',
                                'category' => 'reguler',
                                'status' => 'overtime',
                                'status_note' => 'TEST PAYROLL',
                                'note' => 'TEST PAYROLL',


                            ]);

                            $job_order_has_employee = JobOrderHasEmployee::create([
                                'employee_id' => $e->id,
                                'project_id' => $job_order->project_id,
                                'job_order_id' => $job_order->id,
                            ]);

                            $job_status_has_parent = JobStatusHasParent::create([
                                'parent_id' => $job_order_has_employee->id,
                                'parent_model' => 'App\\Models\\JobOrderHasEmployee',
                                'employee_id' => $job_order_has_employee->employee_id,
                                'job_order_id' => $job_order->id,
                                'status' => 'overtime',
                                'datetime_start' => $hour_overtime_start,
                                'datetime_end' => $hour_overtime_end,
                                'note_end' => 'TES PAYROLL'


                            ]);
                        }
                    }
                }




                if ($e->employee_status == 'aktif') {
                    $attendance  = Attendance::firstOrCreate([
                        'employee_id' => $e->id,
                        'cloud_id' => 12,
                        'date' => $p->format('Y-m-d'),
                    ]);

                    $attendance->update([
                        'roster_daily_id' => $status_roster_id,
                        'roster_status_initial' => $status_roster,
                        'hour_start' => $hour_start,
                        'hour_end' => $hour_end,
                        'duration_work' => $duration_work * 60,
                        'is_koreksi'=>1,

                        'hour_rest_start' => null,
                        'hour_rest_end' => null,
                        'duration_rest' => null,

                        // 'hour_overtime_start' => $hour_overtime_start,
                        // 'hour_overtime_end' => $hour_overtime_end,
                        // 'duration_overtime' => $duration_overtime * 60,
                    ]);

                    if($is_lembur){
                        $attendance->update([
                            // 'roster_daily_id' => $status_roster_id,
                            // 'roster_status_initial' => $status_roster,
                            // 'hour_start' => $hour_start,
                            // 'hour_end' => $hour_end,
                            // 'duration_work' => $duration_work * 60,
                            // 'is_koreksi'=>1,
    
                            // 'hour_rest_start' => null,
                            // 'hour_rest_end' => null,
                            // 'duration_rest' => null,
    
                            'hour_overtime_start' => $hour_overtime_start,
                            'hour_overtime_end' => $hour_overtime_end,
                            'duration_overtime' => $duration_overtime * 60,
                        ]);
                    }


                    // if ($e->id == 1 && ($p->format('Y-m-d') == '2022-06-06')) {
                    //     print("\n\n\t\t UPDATE GAJI POKOK \n\n");
                    //     $e->update([
                    //         'basic_salary' => 4000000
                    //     ]);
                    // }

                    // if ($e->id == 50 && ($p->format('Y-m-d') == '2022-08-06')) {
                    //     $e->update([
                    //         'basic_salary' => 4000000
                    //     ]);
                    // }

                    // if ($e->id == 50 && ($p->format('Y-m-d') == '2022-08-06')) {
                    //     $e->update([
                    //         'employee_status' => 'tidak_aktif'
                    //     ]);
                    // }
                }
            }


            if ($karyawan_tipe_1 >  3 && $karyawan_tipe_2 >  3 && $karyawan_tipe_3 >  3) {
                break;
            }

            $iter++;
            // break;
        }



        for ($i = 1; $i <= 12; $i++) {

            $p = Carbon::parse('2022-' . $i . "-01");
            print("-----GENERATE PAYROLL " . $p->format('d F Y') . "---------------\n");
            $data_period_payroll = (object) [
                "periode" => $p->format('Y-m'),
                "date_end" => $p->startOfMonth()->format('Y-m') . "-25",
                "date_start" => $p->startOfMonth()->addMonths(-1)->format('Y-m') . "-26",

            ];

            // print_r($data_period_payroll);

            $period_payroll =  new PeriodPayrollController($data_period_payroll);

            $period_payroll->store();
        }


        Attendance::whereDate('date','<',Carbon::now())->update([
            'is_final'=>1
        ]);
    }
}
