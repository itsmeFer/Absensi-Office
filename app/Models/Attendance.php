<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'check_in_time', 'check_out_time', 'check_in_location', 'check_in_photo', 'check_out_location', 'status'
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
