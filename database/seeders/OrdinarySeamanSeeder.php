<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdinarySeamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ordinary_seamans')->insert(
            [
                // PT KARYA PACIFIC TEKNIK
                [
                    'name' => 'Pak Rahmat',
                ],
                [
                    'name' => 'Pak Wijad',
                ],
            ],
        );
    }
}
