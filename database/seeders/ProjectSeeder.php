<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(database_path('data/projects_v2.sql')));

        // $dateEndOne = Carbon::now()->addDays(20);
        // $dateEndTwo = Carbon::now()->addDays(15);

        // DB::table('projects')->insert([
        //     [
        //         'company_id' => 1,
        //         'foreman_id' => 1,
        //         'barge_id' => 1,
        //         'name' => 'Project 1',
        //         'date_end' => $dateEndOne,
        //         'day_duration' => 20,
        //         'price' => 25000000,
        //         'down_payment' => 6000000,
        //         'remaining_payment' => null,
        //         'type' => 'contract',
        //         'note' => 'test note',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        //     [
        //         'company_id' => 2,
        //         'foreman_id' => 2,
        //         'barge_id' => 2,
        //         'name' => 'Project 2',
        //         'date_end' => $dateEndTwo,
        //         'day_duration' => 15,
        //         'price' => 15000000,
        //         'down_payment' => 5000000,
        //         'remaining_payment' => null,
        //         'type' => 'daily',
        //         'note' => 'test note',
        //         'created_at' => Carbon::now(),
        //         'updated_at' => Carbon::now(),
        //     ],
        // ]);
    }
}
