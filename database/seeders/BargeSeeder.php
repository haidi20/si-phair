<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('barges')->insert(
            [
                [
                    'name' => 'Kapal A',
                    'description' => 'Lokasi Kapal A',
                ],
                [
                    'name' => 'Kapal B',
                    'description' => 'Lokasi Kapal B',
                ],
                [
                    'name' => 'Kapal C',
                    'description' => 'Lokasi Kapal C',
                ],
            ],
        );
    }
}
