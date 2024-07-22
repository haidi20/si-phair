<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WorkingHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('working_hours')->insert(
            [
                [
                    'start_time' => '06:00',
                    'late_five_two' => '08:00',
                    'late_six_one' => '09:00',
                    'after_work' => '17:00',
                    'after_work_limit' => '20:00',
                    'start_rest' => '11:30',
                    'end_rest' => '13:00',
                    // 'maximum_delay' => '18:00',
                    'fastest_time' => '16:00',
                    'overtime_work' => '17:00',
                    'saturday_work_hour' => '13:00',
                ],
            ],
        );
    }
}
