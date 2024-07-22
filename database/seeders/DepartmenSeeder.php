<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DepartmenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departmens')->insert(
            [
                // PT KARYA PACIFIC TEKNIK
                [
                    'code' => '01',
                    'name' => 'Pengawas/ Supervisor',
                    'description' => 'Pengawas/Supervisor',
                    'company_id' => 1,
                ],
                [
                    'code' => '02',
                    'name' => 'Hrd',
                    'description' => 'Hrd',
                    'company_id' => 1,
                ],
                [
                    'code' => '03',
                    'name' => 'Gudang/Logistics',
                    'description' => 'Gudang/Logistics',
                    'company_id' => 1,
                ],
                [
                    'code' => '04',
                    'name' => 'Mekanik/ Mecanic',
                    'description' => 'Mekanik/Mecanic',
                    'company_id' => 1,
                ],
                [
                    'code' => '05',
                    'name' => 'Electric',
                    'description' => 'Electric',
                    'company_id' => 1,
                ],
                [
                    'code' => '06',
                    'name' => 'Kebun',
                    'description' => 'Kebun',
                    'company_id' => 1,
                ],
                [
                    'code' => '07',
                    'name' => 'Rep. Balon',
                    'description' => 'Rep. Balon',
                    'company_id' => 1,
                ],
                [
                    'code' => '08',
                    'name' => 'Airbag',
                    'description' => 'Airbag',
                    'company_id' => 1,
                ],
                [
                    'code' => '17',
                    'name' => 'Driver',
                    'description' => 'Driver',
                    'company_id' => 1,
                ],
                [
                    'code' => '10',
                    'name' => 'Operator',
                    'description' => 'Operator',
                    'company_id' => 1,
                ],
                [
                    'code' => '09',
                    'name' => 'Bubut',
                    'description' => 'Bubut',
                    'company_id' => 2,
                ],

                // CV KARYA PACIFIC TEKNIK
                // [
                //     'code' => 'CV-01',
                //     'name' => 'Manager',
                //     'description' => 'Manager',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-02',
                //     'name' => 'Ass Manager',
                //     'description' => 'Ass Manager',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-03',
                //     'name' => 'Hrd',
                //     'description' => 'Hrd',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-04',
                //     'name' => 'Pengawas/Supervisor',
                //     'description' => 'Pengawas/Supervisor',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-05',
                //     'name' => 'Acounting',
                //     'description' => 'Acounting',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-06',
                //     'name' => 'Marketing',
                //     'description' => 'Marketing',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-07',
                //     'name' => 'Hygiene',
                //     'description' => 'Hygiene',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-08',
                //     'name' => 'Gudang',
                //     'description' => 'Gudang',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-09',
                //     'name' => 'Bubut',
                //     'description' => 'Bubut',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-10',
                //     'name' => 'Mekanik/ Mecanic',
                //     'description' => 'Mekanik/ Mecanic',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-11',
                //     'name' => 'Electric',
                //     'description' => 'Electric',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-12',
                //     'name' => 'Operator',
                //     'description' => 'Operator',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-13',
                //     'name' => 'Welder',
                //     'description' => 'Welder',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-14',
                //     'name' => 'Fitter',
                //     'description' => 'Fitter',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-15',
                //     'name' => 'Helper',
                //     'description' => 'Helper',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-16',
                //     'name' => 'Kebun',
                //     'description' => 'Kebun',
                //     'company_id' => 2,
                // ],
                // [
                //     'code' => 'CV-17',
                //     'name' => 'Driver',
                //     'description' => 'Driver',
                //     'company_id' => 2,
                // ],
            ],
        );
    }
}
