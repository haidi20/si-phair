<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class salaryAdjustmentDetail extends Model
{
    use HasFactory;

    protected $appends = [
        "employee_name", "position_name",
    ];
    protected $fillable = [
        'salary_adjustment_id',
        'employee_id',
        'type_amount',
        'type_incentive',
        'amount',
        'type_time',
        'month_start',
        'month_end',
        'created_by',
        'updated_by',
        'deleted_by',
        'is_tanggal_merah',
        'tanggal_merah',
        'tanggal_absen_potong_gaji',
        'is_absen_potong_gaji',


    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = request("user_id");
            $model->updated_by = NULL;
        });

        static::updating(function ($model) {
            $model->updated_by = request("user_id");
        });
    }

    public function SalaryAdjustment()
    {
        return $this->belongsTo(salaryAdjustment::class, "salary_adjustment_id", "id");
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }

    public function getEmployeeNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->name;
        }
    }

    public function getPositionNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->position_name;
        }
    }
}
