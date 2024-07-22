<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->insert(
            [
                // PT KARYA PACIFIC TEKNIK SHIPYARD

                // Pengawas/ Supervisor
                [
                    'name' => 'Pengawas',
                    'description' => 'Pengawas',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 1,
                ],
                [
                    'name' => 'Ass Mekanik',
                    'description' => 'Ass Mekanik',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 1,
                ],
                [
                    'name' => 'QC',
                    'description' => 'Quality Control',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 1,
                ],

                // HRD
                [
                    'name' => 'HRD',
                    'description' => 'HRD',
                    'minimum_employee' => 0,
                    // 'departmen_id' => 2,
                ],
                // Gudang/ Logistics
                [
                    'name' => 'Logistics',
                    'description' => 'Logistics',
                    'minimum_employee' => 0,
                    // 'departmen_id' => 3,
                ],
                // Mekanik
                [
                    'name' => 'Mekanik',
                    'description' => 'Mekanik',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 4,
                ],
                [
                    'name' => 'Help Mekanik',
                    'description' => 'Help Mekanik',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 4,
                ],
                // Electric
                [
                    'name' => 'Electric',
                    'description' => 'Electric',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 5,
                ],
                // Kebun
                [
                    'name' => 'Kebun',
                    'description' => 'Kebun',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 6,
                ],
                [
                    'name' => 'Helper',
                    'description' => 'Helper',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 6,
                ],
                // Rep. Balon
                [
                    'name' => 'Rep. Balon',
                    'description' => 'Rep. Balon',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 7,
                ],
                // Airbag
                [
                    'name' => 'Airbag',
                    'description' => 'Airbag',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 8,
                ],
                // Driver
                [
                    'name' => 'Driver',
                    'description' => 'Driver',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 9,
                ],
                // Operator
                [
                    'name' => 'Operator',
                    'description' => 'Operator',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 10,
                ],
                [
                    'name' => 'Kepala Bubut',
                    'description' => 'Kepala Bubut',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 11,
                ],
                [
                    'name' => 'Bubut',
                    'description' => 'Bubut',
                    'minimum_employee' => 1,
                    // 'departmen_id' => 11,
                ],

                // CV KARYA PACIFIC TEKNIK SHIPYARD
                // Manager
                [
                    'name' => 'Manager',
                    'description' => 'Manager',
                    'minimum_employee' => 0,
                ],
                // // Acounting
                [
                    'name' => 'Head of Acounting',
                    'description' => 'Head of Acounting',
                    'minimum_employee' => 0,
                ],
                [
                    'name' => 'Acounting',
                    'description' => 'Acounting',
                    'minimum_employee' => 0,
                ],
                [
                    'name' => 'Purchasing',
                    'description' => 'Purchasing',
                    'minimum_employee' => 0,
                ],
                [
                    'name' => 'Cashier',
                    'description' => 'Cashier',
                    'minimum_employee' => 0,
                ],
                [
                    'name' => 'Admin',
                    'description' => 'Admin',
                    'minimum_employee' => 0,
                ],
                [
                    'name' => 'Admin A/R',
                    'description' => 'Admin A/R',
                    'minimum_employee' => 0,
                ],
                // Marketing
                [
                    'name' => 'Marketing',
                    'description' => 'Marketing',
                    'minimum_employee' => 0,
                ],
                // Hygiene
                [
                    'name' => 'Office Girl',
                    'description' => 'office Girl',
                    'minimum_employee' => 0,
                ],
                // Gudang
                [
                    'name' => 'Head of Warehouse',
                    'description' => 'Head of Warehouse',
                    'minimum_employee' => 0,
                ],
                [
                    'name' => 'Adm. Warehouse',
                    'description' => 'Adm. Warehouse',
                    'minimum_employee' => 0,
                ],
                // // Bubut
                // [
                //     'name' => 'Kepala Bubut',
                //     'description' => 'Kepala Bubut',

                // 'minimum_employee' => 1,              //     'departmen_id' => 19,
                // ],
                // [
                //     'name' => 'Bubut',
                //     'description' => 'Bubut',

                // 'minimum_employee' => 1,              //     'departmen_id' => 19,
                // ],
                // // Mekanik
                // [
                //     'name' => 'Mekanik',
                //     'description' => 'Mekanik',
                //     'minimum_employee' => 1,
                //     //     'departmen_id' => 20,
                // ],
                // [
                //     'name' => 'Helper Mekanik',
                //     'description' => 'Helper Mekanik',
                //     'minimum_employee' => 1,
                //     //     'departmen_id' => 20,
                // ],
                // Electric
                // [
                //     'name' => 'Electric',
                //     'description' => 'Electric',
                //     'minimum_employee' => 1,
                //     //     'departmen_id' => 21,
                // ],
                // Operator Crane
                [
                    'name' => 'Operator Crane',
                    'description' => 'Operator Crane',
                    'minimum_employee' => 1,
                    //     'departmen_id' => 22,
                ],
                // [
                //     'name' => 'Operator',
                //     'description' => 'Operator',
                //     'minimum_employee' => 1,
                //     //     'departmen_id' => 22,
                // ],
                // Welder
                [
                    'name' => 'Welder',
                    'description' => 'Welder',
                    'minimum_employee' => 1,
                    //     'departmen_id' => 23,
                ],
                // Fitter
                [
                    'name' => 'Fitter',
                    'description' => 'Fitter',
                    'minimum_employee' => 0,
                    //     'departmen_id' => 24,
                ],
                // Helper
                // [
                //     'name' => 'Helper',
                //     'description' => 'Helper',
                //     'minimum_employee' => 1,
                //     // 'departmen_id' => 25,
                // ],
                // // Kebun
                // [
                //     'name' => 'Kebun',
                //     'description' => 'Kebun',
                //     'departmen_id' => 26,
                // ],
                // // Driver
                // [
                //     'name' => 'Driver',
                //     'description' => 'Driver',
                //     'departmen_id' => 27,
                // ],
            ],
        );
    }
}
