<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('locations')->insert(
            [
                [
                    'name' => 'Doc 1',
                    'description' => '',
                ],
                [
                    'name' => 'Doc 2',
                    'description' => '',
                ],
            ],
        );
    }
}
