<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $appends = [
        "company_name", "position_name", "departmen_name", "data_finger",
        "location_name", "employee_type_name", "name_and_position", "remaining_time_off",
        "day_cuti_use"
    ];
    protected $fillable = [
        "working_hour",
        "bpjs_jht_company_percent",
        "bpjs_jht_employee_percent",
        "bpjs_jkk_company_percent",
        "bpjs_jkk_employee_percent",
        "bpjs_jkm_company_percent",
        "bpjs_jkm_employee_percent",
        "bpjs_jp_company_percent",
        "bpjs_jp_employee_percent",
        "bpjs_kes_company_percent",
        "bpjs_kes_employee_percent",
        "day_vacation",
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = Schema::getColumnListing($this->getTable());
    }

    public function company()
    {
        return $this->belongsTo(Company::class, "company_id", "id");
    }

    public function roster()
    {
        return $this->hasOne(Roster::class);
    }

    public function rosterDailies()
    {
        return $this->hasMany(RosterDaily::class);
    }

    public function fingers()
    {
        return $this->hasMany(Finger::class, "employee_id", "id")->whereNotNull("id_finger");
    }

    public function finger()
    {
        return $this->hasMany(Finger::class, "employee_id", "id")->whereNotNull("id_finger");
    }

    public function attendanceHasEmployees()
    {
        return $this->hasMany(AttendanceHasEmployee::class);
    }

    public function getCompanyNameAttribute()
    {
        if ($this->company) {
            return $this->company->name;
        } else {
            return "Data Perusahaan Masih Kosong";
        }
    }

    public function position()
    {
        return $this->belongsTo(Position::class, "position_id", "id");
    }

    public function getPositionNameAttribute()
    {
        if ($this->position) {
            return $this->position->name;
        } else {
            return "Data Jabatan Masih Kosong";
        }
    }

    public function departmen()
    {
        return $this->belongsTo(Departmen::class, "departmen_id", "id");
    }

    public function getDepartmenNameAttribute()
    {
        if ($this->departmen) {
            return $this->departmen->name;
        } else {
            return "Data Departemen Masih Kosong";
        }
    }

    public function location()
    {
        return $this->belongsTo(Location::class, "location_id", "id");
    }

    public function getLocationNameAttribute()
    {
        if ($this->location) {
            return $this->location->name;
        } else {
            return "Data Lokasi Masih Kosong";
        }
    }

    public function employee_type()
    {
        return $this->belongsTo(EmployeeType::class, "employee_type_id", "id");
    }

    public function getEmployeeTypeNameAttribute()
    {
        if ($this->employee_type) {
            return $this->employee_type->name;
        } else {
            return "Data Tipe Pegawai Masih Kosong";
        }
    }

    public function finger_tool()
    {
        return $this->belongsTo(FingerTool::class, "finger_tool_id", "id");
    }

    public function getNameAndPositionAttribute()
    {
        if ($this->position) {
            return $this->name . " ({$this->position_name})";
        }
    }

    public function scopeActive($query)
    {
        return $query->where("employee_status", "aktif");
    }

    function getDayCutiUseAttribute()
    {
        return $old_cuti = \DB::table('vacations')->select(
            DB::raw('COALESCE(sum(DATEDIFF(date_end, date_start)+1),0) AS n_day')
        )->whereYear('date_start', Carbon::now()->format('Y'))
            ->where('status', 'accept')
            ->where('employee_id', $this->id)
            ->first()->n_day ?? 0;

        // return $this->day_vacation - ($old_cuti);
    }

    function getRemainingTimeOffAttribute()
    {

        // return [$this->day_vacation , $this->day_cuti_use];
        $result = $this->day_vacation - abs($this->day_cuti_use);
        if ($result < 0) {
            $result = 0;
        }

        return $result;
    }

    public function getDataFingerAttribute()
    {
        return $this->fingers->map(function ($query) {
            $query['pin'] = $query->id_finger;
            $query['cloud_id'] = $query->cloud_id;

            return $query;
        });
    }
}
