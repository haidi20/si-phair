<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class Finger extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['employee_id', 'finger_tool_id', 'id_finger'];
    protected $appends = [
        "cloud_id",
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = Schema::getColumnListing($this->getTable());
    }

    public function employee()
    {
        // Employee
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }

    public function finger_tool()
    {
        return $this->belongsTo(FingerTool::class, "finger_tool_id", "id");
    }

    public function getCloudIdAttribute()
    {
        if ($this->finger_tool) {
            return $this->finger_tool->cloud_id;
        }
    }
}
