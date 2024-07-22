<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class SalaryAdvance extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        'employee_name', 'creator_name', 'loan_amount_readable', 'position_name',
        'monthly_deduction_readable', 'employee_name_and_position',
        'remaining_debt_readable', 'remaining_debt',
        'month_loan_complite_readable',
        // 'status_readable', 'status_color',
    ];

    protected $fillable = [
        'employee_id',
        'approval_level_id',
        'loan_amount',
        'monthly_deduction',
        'duration',
        'remaining_salary',
        'reason',
        'note',
        'status',
        'payment_status',
        'payment_method',
        'month_loan_complite',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = Schema::getColumnListing($this->getTable());
    }

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

    // public function salaryAdvanceLasts()
    // {
    //     return $this->hasMany(SalaryAdvance::class, "employee_id", "employee_id")
    //         ->where('created_at', '<', $this->created_at);
    // }

    public function salaryAdvanceAmounts()
    {
        return $this->hasMany(SalaryAdvanceAmount::class, "salary_advance_id", "id");
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, "employee_id", "id")->select("id", "name", "position_id");
    }

    public function foreman()
    {
        return $this->belongsTo(Employee::class, "foreman_id", "id");
    }

    public function creator()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function approvalAgreement()
    {
        return $this->belongsTo(ApprovalAgreement::class, "id", "model_id")
            ->where("name_model", "App\Models\SalaryAdvance");
    }

    public function getEmployeeNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->name;
        }
    }

    public function getEmployeeNameAndPositionAttribute()
    {
        if ($this->employee) {
            return $this->employee->name_and_position;
        }
    }

    public function getCreatorNameAttribute()
    {
        if ($this->creator) {
            return $this->creator->name;
        }
    }


    public function getLoanAmountReadableAttribute()
    {
        $loanAmount = number_format($this->loan_amount, 0, ',', '.');
        return "Rp {$loanAmount}";
    }

    public function getPositionNameAttribute()
    {
        if ($this->employee) {
            return $this->employee->position_name;
        } else {
            return null;
        }
    }

    public function getMonthlyDeductionReadAbleAttribute()
    {
        $loanAmount = number_format($this->monthly_deduction, 0, ',', '.');
        return "Rp {$loanAmount}";
    }

    public function getMonthLoanCompliteReadableAttribute()
    {
        return Carbon::parse($this->month_loan_complite)->locale('id')->isoFormat("MMMM YYYY");
    }

    public function getRemainingDebtAttribute()
    {
        $now = Carbon::now();
        $day = $now->format("d");
        $salaryAdanceMount = SalaryAdvanceAmount::where([
            "salary_advance_id" => $this->id,
            "employee_id" => $this->employee_id,
        ]);

        if ($day >= 26) {
            $salaryAdanceMount = $salaryAdanceMount
                ->where("month", ">=", $now->addMonth()->startOfMonth());
        } else {
            $salaryAdanceMount = $salaryAdanceMount
                ->where("month", ">=", $now->startOfMonth());
        }

        return $salaryAdanceMount = $salaryAdanceMount->sum("nominal");
        // return $salaryAdanceMount = $salaryAdanceMount->pluck("month");
    }

    public function getRemainingDebtReadableAttribute()
    {
        $salaryAdvanceLasts = SalaryAdvance::where("employee_id", $this->employee_id)
            ->where("created_at", "<", $this->created_at);

        $checkLastData = $salaryAdvanceLasts->count();

        if ($checkLastData > 0) {
            $sumReaminingDebt = 0;

            foreach ($salaryAdvanceLasts->get() as $index => $item) {
                $sumReaminingDebt += $item->remaining_debt;
                // $sumReaminingDebt = $item->remaining_debt;
            }

            // return $sumReaminingDebt;
            return  "Rp. " . number_format($sumReaminingDebt, 0, ',', '.');
        } else {
            return "Rp. 0";
        }
    }

    // public function getStatusReadableAttribute()
    // {
    //     $getStatus = Config::get("library.status.{$this->status}");

    //     return $getStatus["readable"];
    // }

    // public function getStatusColorAttribute()
    // {
    //     $getStatus = Config::get("library.status.{$this->status}");

    //     return $getStatus["color"];
    // }
}
