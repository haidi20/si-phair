<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BpjsCalculationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bpjs_calculations')->insert(
            [
                [
                    'name' => 'Hari Tua (HT)',
                    'code'=>'jht',
                    'company_percent' => '3.70',
                    'employee_percent' => '2.00',
                    'company_nominal' => '125597',
                    'employee_nominal' => '67890',
                ],
                [
                    'name' => 'Kecelakaan (JKK)',
                    'code'=>'jkk',
                    'company_percent' => '1.74',
                    'employee_percent' => '0.00',
                    'company_nominal' => '59065',
                    'employee_nominal' => '0',
                ],
                [
                    'name' => 'Kematian (JKM)',
                    'code'=>'jkm',
                    'company_percent' => '0.30',
                    'employee_percent' => '0.00',
                    'company_nominal' => '10184',
                    'employee_nominal' => '0',
                ],
                [
                    'name' => 'Pensiun (JP)',
                    'code'=>'jp',
                    'company_percent' => '2.00',
                    'employee_percent' => '1.00',
                    'company_nominal' => '67890',
                    'employee_nominal' => '33945',
                ],
                [
                    'name' => 'Kesehatan (BPJS)',
                    'code'=>'kes',
                    'company_percent' => '4.00',
                    'employee_percent' => '1.00',
                    'company_nominal' => '135781',
                    'employee_nominal' => '33945',
                ],
            ],
        );
    }
}
