<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'check_in',
        'check_out',
        'check_in_location',
        'check_out_location',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}