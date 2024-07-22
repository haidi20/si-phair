<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FingerSeeder extends Seeder
{
    public function run()
    {
        DB::unprepared(file_get_contents(database_path('data/finger.sql')));
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function runOld()
    {
        DB::unprepared(file_get_contents(database_path('data/fingers.sql')));
        // DB::table('fingers')->insert(
        //     [
        //         [
        //             'employee_id' => '14',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '1',
        //         ],
        //         [
        //             'employee_id' => '24',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '10',
        //         ],
        //         [
        //             'employee_id' => '28',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '11',
        //         ],
        //         [
        //             'employee_id' => '27',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '12',
        //         ],
        //         [
        //             'employee_id' => '12',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '13',
        //         ],
        //         [
        //             'employee_id' => '4',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '14',
        //         ],
        //         [
        //             'employee_id' => '9',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '15',
        //         ],
        //         [
        //             'employee_id' => '6',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '16',
        //         ],
        //         [
        //             'employee_id' => '32',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '17',
        //         ],
        //         // DATA PEGAWAI RIUS TIDAK ADA
        //         // [
        //         //     'employee_id' => '14',
        //         //     'finger_tool_id' => '2',
        //         //     'id_finger' => '18',
        //         // ],
        //         [
        //             'employee_id' => '21',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '19',
        //         ],
        //         // DATA PEGAWAI YOSEP TIDAK ADA
        //         // [
        //         //     'employee_id' => '14',
        //         //     'finger_tool_id' => '2',
        //         //     'id_finger' => '2',
        //         // ],
        //         [
        //             'employee_id' => '40',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '20',
        //         ],
        //         // DATA PEGAWAI ADAM TIDAK ADA
        //         // [
        //         //     'employee_id' => '14',
        //         //     'finger_tool_id' => '2',
        //         //     'id_finger' => '21',
        //         // ],
        //         // DATA PEGAWAI JEFRIYANTO TIDAK ADA
        //         // [
        //         //     'employee_id' => '14',
        //         //     'finger_tool_id' => '2',
        //         //     'id_finger' => '22',
        //         // ],
        //         [
        //             'employee_id' => '11',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '23',
        //         ],
        //         [
        //             'employee_id' => '3',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '24',
        //         ],
        //         [
        //             'employee_id' => '10',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '25',
        //         ],
        //         [
        //             'employee_id' => '35',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '26',
        //         ],
        //         // DATA PEGAWAI SONY TIDAK ADA
        //         // [
        //         //     'employee_id' => '14',
        //         //     'finger_tool_id' => '2',
        //         //     'id_finger' => '27',
        //         // ],
        //         [
        //             'employee_id' => '29',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '28',
        //         ],
        //         // DATA PEGAWAI RUBIAGUSTAMI TIDAK ADA
        //         // [
        //         //     'employee_id' => '14',
        //         //     'finger_tool_id' => '2',
        //         //     'id_finger' => '29',
        //         // ],
        //         [
        //             'employee_id' => '26',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '3',
        //         ],
        //         [
        //             'employee_id' => '18',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '30',
        //         ],
        //         [
        //             'employee_id' => '17',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '31',
        //         ],
        //         // DATA PEGAWAI FIRMAN TIDAK ADA
        //         // [
        //         //     'employee_id' => '29',
        //         //     'finger_tool_id' => '2',
        //         //     'id_finger' => '32',
        //         // ],
        //         [
        //             'employee_id' => '7',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '33',
        //         ],
        //         [
        //             'employee_id' => '34',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '34',
        //         ],
        //         [
        //             'employee_id' => '25',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '35',
        //         ],
        //         [
        //             'employee_id' => '16',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '36',
        //         ],
        //         [
        //             'employee_id' => '47',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '37',
        //         ],
        //         [
        //             'employee_id' => '5',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '38',
        //         ],
        //         [
        //             'employee_id' => '37',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '39',
        //         ],
        //         [
        //             'employee_id' => '15',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '4',
        //         ],
        //         [
        //             'employee_id' => '34',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '5',
        //         ],
        //         [
        //             'employee_id' => '19',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '6',
        //         ],
        //         [
        //             'employee_id' => '31',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '7',
        //         ],
        //         [
        //             'employee_id' => '30',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '8',
        //         ],
        //         [
        //             'employee_id' => '23',
        //             'finger_tool_id' => '2',
        //             'id_finger' => '9',
        //         ],
        //     ],
        // );
    }
}
