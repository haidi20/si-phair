<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Bumdes::truncate();
        DB::unprepared(file_get_contents(database_path('data/customers.sql')));

        // $csvFile = fopen(base_path("database/data/customer.csv"), "r");

        // $firstline = true;
        // while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
        //     if (!$firstline) {
        //         $customer = new Customer([
        //             "code" => $data[0],
        //             "name" => $data[1],
        //             "address" => $data[2],
        //             "terms" => $data[3],
        //             "credit_limits" => $data[4],
        //             "contact_person" => $data[5],
        //             "handphone" => $data[6],
        //             "telephone" => $data[7],
        //         ]);
        //         $customer->save();
        //         // break;
        //     }
        //     $firstline = false;
        // }

        // fclose($csvFile);
    }
}
