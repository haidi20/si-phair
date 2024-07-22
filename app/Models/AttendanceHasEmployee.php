<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceHasEmployee extends Model
{
    use HasFactory;

    // protected $table = 'attendance_has_employees';

    protected $appends = [
        "duration_work_readable",  "duration_rest_readable",  "duration_overtime_readable",
        "duration_overtime_job_order_readable", "employee_name", "position_name", "working_hour_late",
    ];

    protected $fillable = [
        'date',
        'employee_id',
        'hour_start',
        'hour_end',
        'duration_work',
        'hour_rest_start',
        'hour_rest_end',
        'duration_rest',
        'hour_overtime_start',
        'hour_overtime_end',
        'duration_overtime',
        'hour_overtime_job_order_start',
        'hour_overtime_job_order_end',
        'duration_overtime_job_order',
        'lembur_kali_satu_lima',
        'lembur_kali_dua',
        'lembur_kali_tiga',
        'lembur_kali_empat',
        'is_weekend',
        'is_vacation',
        'is_payroll_use',
        'payroll_id',
    ];

    // protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, "employee_id", "id")
            ->select("id", "name", "position_id", "working_hour")
            ->active();
    }

    // nama user yang approval
    public function getEmployeeNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->name;
        }
    }

    // departemen user yang approval
    public function getPositionNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->position_name;
        }
    }

    public function getDurationWorkReadableAttribute()
    {
        $result = null;

        if ($this->hour_start != null && $this->hour_end != null) {
            $result = dateTimeDuration($this->hour_start, $this->hour_end, true);
        }

        return $result;
    }

    public function getDurationRestReadableAttribute()
    {
        $result = null;

        if ($this->hour_rest_start != null && $this->hour_rest_end != null) {
            $result = dateTimeDuration($this->hour_rest_start, $this->hour_rest_end, true);
        }

        return $result;
    }

    public function getDurationOvertimeReadableAttribute()
    {
        $result = null;

        if ($this->hour_overtime_start != null && $this->hour_overtime_end != null) {
            $result = dateTimeDuration($this->hour_overtime_start, $this->hour_overtime_end, true);
        }

        return $result;
    }

    public function getDurationOvertimeJobOrderReadableAttribute()
    {
        $result = null;

        if ($this->hour_overtime_job_order_start != null && $this->hour_overtime_job_order_end != null) {
            $result = dateTimeDuration($this->hour_overtime_job_order_start, $this->hour_overtime_job_order_end, true);
        }

        return $result;
    }

    public function getWorkingHourLateAttribute()
    {
        if ($this->employee) {
            $workingHour = WorkingHour::first()->late_five_two;

            if ($this->employee->working_hour == '6,1') {
                $workingHour = WorkingHour::first()->late_six_one;
            }

            return $workingHour;
        }
    }
}
