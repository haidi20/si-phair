<?php

namespace Database\Seeders;

// use CreateAttendanceHasEmployeesTable;

use App\Models\Attendance;
use App\Models\AttendanceFingerspot;
use App\Models\BpjsCalculation;
use App\Models\Employee;
use App\Models\salaryAdjustment;
use App\Models\salaryAdjustmentDetail;
use App\Models\Vacation;
use Illuminate\Database\Seeder;

use Carbon\Carbon;
use Carbon\CarbonPeriod;


class DummyAbsenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    // php artisan db:seed --class=DummyAbsenSeeder
    public function run()
    {

        $es = Employee::where('is_aktif',1)->get();

        foreach ($es as $key => $e) {
            $e->update([
                ''
            ]);
        }

        // $datas = salaryAdjustment::where('name','like','%HARI KERJA -1%')->get();

        // foreach ($datas as $key => $d) {
        //     salaryAdjustmentDetail::where('salary_adjustment_id',$d->id)->delete();
        // }

        // $datas = array(
        //     'id'=>64,
        //     'sisa_cuti'=>4
        // );

        // BpjsCalculation
        // $es = Employee::get();
        

        // foreach ($es as $key => $e) {
        //     print("Nama : ".$e->name." || Sisa : ".$e->remaining_time_off."\n");
        //     if($e->remaining_time_off > 0){
        //         print("Penghabisan CUTI : ".$e->name." || Sisa : ".$e->remaining_time_off."\n");
        //         Vacation::create([
        //             'date_start'=>'2023-06-26',
        //             'date_end'=>'2023-07-25',
        //             'status'=>'accept',
        //             'note'=>'CUTI AUTO APPROVE SYSTEM',
        //             'employee_id'=>$e->id,
        //         ]);
        //     }
        // }

        



        // CreateAttendanceHasEmployeesTable

    }
}
