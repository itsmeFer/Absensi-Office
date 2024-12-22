<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();
        $todayAttendance = Attendance::with('employee') // Eager load employee relation
            ->whereDate('check_in', today())
            ->get();
            
        $allAttendances = Attendance::with('employee')
            ->orderBy('check_in', 'desc')
            ->get();
            
        return view('admin.dashboard', compact('users', 'todayAttendance', 'allAttendances'));
    }
}