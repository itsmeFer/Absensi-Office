<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'check_in',
        'check_out',
        'status',
        'check_in_location',
        'check_out_location',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    // Relasi ke User (karyawan)
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
