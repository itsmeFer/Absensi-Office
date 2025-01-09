<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'check_in',
        'check_out',
        'check_in_location',
        'check_out_location',
        'status',
    ];

    // Konversi otomatis ke Carbon
    protected $dates = [
        'check_in',
        'check_out'
    ];

    // Accessor untuk check_in
    public function getCheckInAttribute($value)
    {
        return $value ? Carbon::parse($value)->setTimezone('Asia/Jakarta') : null;
    }

    // Accessor untuk check_out
    public function getCheckOutAttribute($value)
    {
        return $value ? Carbon::parse($value)->setTimezone('Asia/Jakarta') : null;
    }
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}