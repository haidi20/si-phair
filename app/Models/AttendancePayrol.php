<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendancePayrol extends Model
{
    use HasFactory;

    protected $table = 'attendance';
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class, "employee_id", "id");
    }



   
}
