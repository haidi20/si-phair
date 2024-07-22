<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employee_types')->insert(
            [
                // PT KARYA PACIFIC TEKNIK
                [
                    'name' => 'TETAP',
                    'description' => 'Karyawan Tetap',
                ],
                [
                    'name' => 'KONTRAK',
                    'description' => 'Karyawan Kontrak',
                ],
                [
                    'name' => 'HARIAN',
                    'description' => 'Karyawan Harian',
                ],
            ],
        );
    }
}
