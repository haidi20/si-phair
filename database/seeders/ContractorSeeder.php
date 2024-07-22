<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contractors')->insert(
            [
                // PT KARYA PACIFIC TEKNIK
                [
                    'name' => 'Pak Ramli',
                    'address' => null,
                    'no_hp' => null,
                ],
                [
                    'name' => 'Pak Samsudin',
                    'address' => null,
                    'no_hp' => null,
                ],
            ],
        );
    }
}
