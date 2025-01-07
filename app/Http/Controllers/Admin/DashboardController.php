<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        $todayAttendance = Attendance::select('id', 'employee_id', 'check_in')
            ->with('employee:id,name') // Batasi data employee
            ->whereDate('check_in', today())
            ->get();
        
        $allAttendances = Attendance::with('employee')
            ->orderBy('check_in', 'desc')
            ->get();

        return view('admin.dashboard', compact('users', 'todayAttendance', 'allAttendances'));
    }
}
