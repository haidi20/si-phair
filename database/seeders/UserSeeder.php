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
        $user = User::create([
            'name' => 'user',
            'email' => 'user@email.com',
            'password' => Hash::make('user'),
            'role_id' => 1,
            'status' => true,
        ]);
    }
}
