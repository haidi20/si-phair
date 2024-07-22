<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /*
            data master merupakan data wajib ada, jadi harus di input ulang ketika migrate ulang.
        */
        // start data master

        // di ganti dengan mysql data/role_permission.sql
        $this->call(FeatureSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(UserSeeder::class);


        // $this->call(RosterStatusSeeder::class);
        // $this->call(CompanySeeder::class);
        // $this->call(DepartmenSeeder::class);
        // $this->call(PositionSeeder::class);
        // $this->call(WorkingHourSeeder::class);
        // $this->call(LocationSeeder::class);
        // $this->call(BargeSeeder::class);
        // $this->call(FingerToolSeeder::class);
        // $this->call(EmployeeTypeSeeder::class);
        // $this->call(ApprovalSeeder::class);
        // $this->call(BpjsCalculationSeeder::class);
        // $this->call(BaseWagesBpjsSeeder::class);
        // $this->call(EmployeeSeeder::class);
        // $this->call(JobSeeder::class);
        // $this->call(SalaryAdjustmentSeeder::class);
        // $this->call(FingerSeeder::class);
        // $this->call(JobOrderSeeder::class);
        // $this->call(JobStatusHasParent::class);
        // $this->call(JobOrderHasEmployeeSeeder::class);

        // di ganti dengan mysql data/attendance.sql
        // $this->call(AttendanceFingerspotSeeder::class);
        // $this->call(AttendanceHasEmployeeSeeder::class);
        // $this->call(AttendanceSeeder::class);
        // $this->call(ProjectSeeder::class);
        // $this->call(CustomerSeeder::class);

        // end master

        /*
            data biasa merupakan data dummy untuk testing fitur, percobaan, dan latihan.
            ketika production data ini tidak wajib diinput
        */
        // start data biasa
        // $this->call(ContractorSeeder::class);
        // $this->call(OrdinarySeamanSeeder::class);
        // $this->call(SalaryAdvanceSeeder::class);
        $this->call(JobOrderSeeder::class);
        // end data biasa
    }
}
