<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FingerToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('finger_tools')->insert(
            [
                [
                    'name' => 'Alat Finger DOC 1',
                    'serial_number' => 'Fio66208021230850',
                    'cloud_id' => 'C2608476E7362425',
                ],
                [
                    'name' => 'Alat Finger DOC 2',
                    'serial_number' => 'Fio66208022030036',
                    'cloud_id' => 'C26118515714322D',
                ],
            ],
        );
    }
}
