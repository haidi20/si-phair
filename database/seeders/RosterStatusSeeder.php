<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RosterStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roster_statuses')->insert(
            [
                [
                    'name' => 'Sakit',
                    'initial' => 'S',
                    'color' => '#FF45ED',
                    'note' => 'tidak hadir karena sakit',
                    'created_by' => 1,
                    'updated_by' => null,
                    'deleted_by' => null,
                    'created_at' => '2023-01-14 05:25:40',
                    'updated_at' => '2023-01-14 05:25:40',
                    'deleted_at' => null,
                ],
                [
                    'name' => 'Ijin',
                    'initial' => 'I',
                    'color' => '#F3E43A',
                    'note' => 'tidak hadir dan Ijin',
                    'created_by' => 1,
                    'updated_by' => null,
                    'deleted_by' => null,
                    'created_at' => '2023-01-04 04:50:07',
                    'updated_at' => '2023-02-16 05:42:16',
                    'deleted_at' => null,
                ],
                [
                    'name' => 'Masuk',
                    'initial' => 'M',
                    'color' => '#8CF73B',
                    'note' => 'Masuk Kerja',
                    'created_by' => 1,
                    'updated_by' => null,
                    'deleted_by' => null,
                    'created_at' => '2023-01-04 07:05:35',
                    'updated_at' => '2023-01-12 02:03:58',
                    'deleted_at' => null,
                ],
                [
                    'name' => 'Cuti',
                    'initial' => 'C',
                    'color' => '#FDA900',
                    'note' => 'jadwal cuti karyawan',
                    'created_by' => 1,
                    'updated_by' => null,
                    'deleted_by' => null,
                    'created_at' => '2023-01-11 11:21:18',
                    'updated_at' => '2023-01-11 11:21:18',
                    'deleted_at' => null,
                ],
                [
                    'name' => 'OFF',
                    'initial' => 'OFF',
                    'color' => '#FD0040',
                    'note' => 'off karyawan',
                    'created_by' => 1,
                    'updated_by' => null,
                    'deleted_by' => null,
                    'created_at' => '2023-01-11 11:21:18',
                    'updated_at' => '2023-01-11 11:21:18',
                    'deleted_at' => null,
                ],
            ],
        );
    }
}
