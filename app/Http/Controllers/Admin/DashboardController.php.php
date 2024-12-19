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
        $todayAttendance = Attendance::whereDate('check_in', today())->get();
        
        return view('admin.dashboard', compact('users', 'todayAttendance'));
    }
}