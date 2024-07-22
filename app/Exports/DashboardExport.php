<?php

namespace App\Exports;

use App\Exports\Sheets\DashboardHighestJobOrderSheet;
use App\Exports\Sheets\DashboardNotYetJobOrderSheet;
use App\Exports\Sheets\DashboardTotalBaseOnPositionSheet;
use App\Exports\Sheets\RosterMainSheet;
use App\Exports\Sheets\RosterTotalSheet;
use App\Models\DashboardHasPosition;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DashboardExport implements WithMultipleSheets
{

    private $employeeNotYetJobOrders;
    private $fiveEmployeeHighestJobOrders;
    private $totalEmployeeBaseOnPositions;


    function __construct($employeeNotYetJobOrders, $fiveEmployeeHighestJobOrders, $totalEmployeeBaseOnPositions)
    {
        $this->employeeNotYetJobOrders = $employeeNotYetJobOrders;
        $this->fiveEmployeeHighestJobOrders = $fiveEmployeeHighestJobOrders;
        $this->totalEmployeeBaseOnPositions = $totalEmployeeBaseOnPositions;
    }

    public function sheets(): array
    {
        $sheets = [];

        $employeeNotYetJobOrders = $this->employeeNotYetJobOrders;
        $fiveEmployeeHighestJobOrders = $this->fiveEmployeeHighestJobOrders;
        $totalEmployeeBaseOnPositions = $this->totalEmployeeBaseOnPositions;

        $sheets[] = new DashboardNotYetJobOrderSheet($employeeNotYetJobOrders);
        $sheets[] = new DashboardHighestJobOrderSheet($fiveEmployeeHighestJobOrders);
        $sheets[] = new DashboardTotalBaseOnPositionSheet($totalEmployeeBaseOnPositions);

        return $sheets;
    }
}
