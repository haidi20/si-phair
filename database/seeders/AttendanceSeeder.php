<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::unprepared(file_get_contents(database_path('data/attendance_all_v2.sql')));
        // DB::unprepared(file_get_contents(database_path('data/attendance_exists_overtime.sql')));
    }
}
