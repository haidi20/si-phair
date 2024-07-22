<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalaryAdvanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('salary_advances')->insert(
        //     [
        //         'approval_level_id' => 1,
        //         'employee_id' => 93,
        //         'loan_amount' => 2000000,
        //         'reason' => 'beli HP',
        //         'created_by' => 1,
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        // );
        DB::unprepared(file_get_contents(database_path('data/salary_advance.sql')));
    }
}
