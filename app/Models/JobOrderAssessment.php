<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrderAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'job_order_id',
        'image',
        'note',
        'date_time',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $appends = [
        "group_name", "employee_name", "position_name", "datetime_readable",
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

    public function creator()
    {
        return $this->belongsTo(User::class, "created_by", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "employee_id", "id");
    }

    public function getGroupNameAttribute()
    {
        if ($this->creator) {
            $groupName = $this->creator->group_name;

            return $groupName == "Quality Control" ? "QC" : $groupName;
        }
    }

    public function getEmployeeNameAttribute()
    {
        if ($this->user) {
            return $this->user->name;
        }
    }

    public function getPositionNameAttribute()
    {
        if ($this->user) {
            return $this->user->position_name;
        }
    }

    public function getDatetimeReadableAttribute()
    {
        return Carbon::parse($this->datetime)->locale('id')->isoFormat("dddd, D MMMM YYYY HH:mm");
    }
}
