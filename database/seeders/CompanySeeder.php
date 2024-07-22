<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert(
            [
                // PT KARYA PACIFIC TEKNIK
                [
                    'name' => 'PT. KARYA PACIFIC TEHNIK SHIPYARD',
                    'description' => 'Perusahaan PT. KARYA PACIFIC TEHNIK SHIPYARD',
                ],
                [
                    'name' => 'CV. KARYA PACIFIC TEHNIK',
                    'description' => 'Perusahaan CV. KARYA PACIFIC TEHNIK',
                ]
            ],
        );
    }
}
