<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json_data_job = File::get("database/data/job.json");
        $data_job = json_decode($json_data_job);

        // dd($data_job);

        foreach ($data_job as $key => $job) {
            Job::create([
                'code' => $job->code,
                'name' => $job->name,
            ]);
        }
    }
}
