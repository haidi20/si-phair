<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roleSuperAdmin = Role::where(['name' => 'Super Admin'])->first();
        $roleSuperAdmin->givePermissionTo(Permission::all());

        // $permissionPrivate = Config("library.permission_private");
        // $permissionAdmin = Permission::whereNotIn("name", $permissionPrivate)->pluck("name")->toArray();
        // $permissionAdmin = array_map('strtolower', $permissionAdmin);
        // $roleAdmin = Role::create(['name' => 'Admin']);
        // $roleAdmin->givePermissionTo($permissionAdmin);

        // // karyawan office
        // $permissionGeneralOffice = [
        //     "lihat dashboard", "lihat laporan kasbon", "persetujuan laporan kasbon", "perwakilan laporan kasbon",
        // ];

        // // pengawas
        // $permissionForeman = [
        //     "lihat dashboard", "lihat job order", "tambah job order",
        // ];

        // $permissionQualityControl = [
        //     "lihat dashboard", "lihat job order",
        // ];

        // $roleHrd = Role::create(['name' => 'HRD']);
        // $roleHrd->givePermissionTo($permissionGeneralOffice);

        // $roleCashier = Role::create(['name' => 'Kasir']);
        // $roleCashier->givePermissionTo($permissionGeneralOffice);

        // $roleForeman = Role::create(['name' => 'Pengawas']);
        // $roleForeman->givePermissionTo($permissionForeman);

        // $roleQc = Role::create(['name' => 'Quality Control']);
        // $roleQc->givePermissionTo($permissionQualityControl);
    }
}
