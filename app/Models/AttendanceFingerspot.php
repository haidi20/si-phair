<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceFingerspot extends Model
{
    use HasFactory;

    protected $fillable = [
        'pin',
        'cloud_id',
        'scan_date',
        'verify',
        'status_scan',
    ];

    protected $appends = [
        "datetime_readable",
    ];

    public function getDatetimeReadableAttribute()
    {
        return Carbon::parse($this->scan_date)->locale('id')->isoFormat("dddd, D MMMM YYYY HH:mm");
    }
}
