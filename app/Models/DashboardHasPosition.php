<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardHasPosition extends Model
{
    use HasFactory;

    protected $appends = [
        "position_name",
    ];

    public function position()
    {
        return $this->belongsTo(Position::class, "position_id", "id");
    }

    public function getPositionNameAttribute()
    {
        if ($this->position) {
            return $this->position->name;
        }
    }
}
