<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class ProjectSimple extends Model
{
    use HasFactory, SoftDeletes;

    // untuk kebutuhan di job order
    // menggunakan model project.php sangat berat load datanya
    // ada kemungkinan pengaruh pada relation table

    protected $table = "projects";

    protected $appends = [
        "location_name",
        "date_start_readable",
        "date_end_readable",
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, "location_id", "id");
    }

    public function getLocationNameAttribute()
    {
        if ($this->location) {
            return $this->location->name;
        } else {
            return null;
        }
    }

    public function getDateStartReadableAttribute()
    {
        if ($this->date_start != null) {
            return dateReadable($this->date_start);
        } else {
            return null;
        }
    }


    public function getDateEndReadableAttribute()
    {
        if ($this->date_end != null) {
            return dateReadable($this->date_end);
        } else {
            return null;
        }
    }
}
