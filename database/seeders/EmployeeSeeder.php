<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Employee;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Bumdes::truncate();

        $csvFile = fopen(base_path("database/data/employee.csv"), "r");

        $firstline = true;
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if (!$firstline) {
                $employee = new Employee([
                    "ptkp"=>"TK/2",
                    "ptkp_karyawan"=>63000000,

                    "bpjs_jht"=>"Y",
                    "bpjs_jkk"=>"Y",
                    "bpjs_jkm"=>"Y",
                    "bpjs_jp"=>"Y",
                    "bpjs_kes"=>"Y",

                    "nip" => $data[1],
                    "nik" => $data[2],
                    "name" => $data[3],
                    "photo" => NULL,
                    "enter_date" => Carbon::now(),
                    "company_id" => $data[4],
                    "position_id" => $data[5],
                    // "finger_doc_1" => $data[6],
                    "basic_salary"=>$data[8],
                    "overtime_rate_per_hour"=>$data[9],
                    "allowance"=>$data[10],
                    "meal_allowance_per_attend"=>$data[11],
                    "transport_allowance_per_attend"=>$data[12],
                    "attend_allowance_per_attend"=>$data[13],
                ]);

                // Mengambil waktu pembuatan data dari entitas yang sesuai di database
                $existingEmployee = Employee::where('nip', $data[1])->first();
                if ($existingEmployee) {
                    $employee->enter_date = $existingEmployee->created_at;
                }

                $employee->save();
                // break;
            }
            $firstline = false;
        }

        fclose($csvFile);
    }
}
