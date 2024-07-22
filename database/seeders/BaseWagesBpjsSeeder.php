<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BaseWagesBpjsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('base_wages_bpjs')->insert(
            [
                [
                    'name' => 'Dasar Upah BPJS TK',
                    'code'=>'tk',
                    'nominal' => '3394315',
                ],
                [
                    'name' => 'Dasar Upah BPJS KES',
                    'code'=>'kes',
                    'nominal' => '3394315',
                ],
            ],
        );
    }
}
