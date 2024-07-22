<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    //  php artisan db:seed --class=UserSeeder
    public function run()
    {
        User::where('email', 'evi@gmail.com')->update([
            'password' => Hash::make('samarinda')
        ]);

        $superadmin = User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@email.com',
            'password' => Hash::make('samarinda'),
            'role_id' => 1,
            'status' => true,
        ]);

        $superadmin->assignRole('Super Admin');

        // $admin = User::create([
        //     'name' => 'admin',
        //     'email' => 'admin@email.com',
        //     'password' => Hash::make('samarinda'),
        //     'role_id' => 2,
        //     'status' => true,
        // ]);

        // $admin->assignRole('Admin');

        // $hrd = User::create([
        //     'name' => 'arini',
        //     'email' => 'hrd@email.com',
        //     'password' => Hash::make('samarinda'),
        //     'role_id' => 3,
        //     'status' => true,
        // ]);

        // $hrd->assignRole('HRD');

        // $cashier = User::create([
        //     'name' => 'indah',
        //     'email' => 'cashier@email.com',
        //     'password' => Hash::make('samarinda'),
        //     'role_id' => 4,
        //     'status' => true,
        // ]);

        // $cashier->assignRole('Kasir');

        // $foreman = User::create([
        //     'name' => 'pengawas',
        //     'email' => 'foreman@email.com',
        //     'password' => Hash::make('samarinda'),
        //     'role_id' => 5,
        //     'status' => true,
        // ]);

        // $foreman->assignRole('Pengawas');

        // $qualityControl = User::create([
        //     'name' => 'qc',
        //     'email' => 'qualityControl@email.com',
        //     'password' => Hash::make('samarinda'),
        //     'role_id' => 6,
        //     'status' => true,
        // ]);

        // $qualityControl->assignRole('Quality Control');
    }
}
