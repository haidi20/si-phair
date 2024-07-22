<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class SalaryAdvanceAmount extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_advance_id',
        'employee_id',
        'month',
        'nominal',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
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
}
