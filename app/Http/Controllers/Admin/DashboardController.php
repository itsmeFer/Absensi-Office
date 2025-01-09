<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data semua pengguna dengan peran selain 'admin'
        $users = User::where('role', '!=', 'admin')->get();

        // Ambil data absensi hari ini
        $todayAttendance = Attendance::with('employee')
            ->whereDate('check_in', today())
            ->get();

        return view('admin.dashboard', compact('users', 'todayAttendance'));
    }

    public function updateAttendanceStatus(Attendance $attendance)
    {
        // Update status absensi
        $attendance->status = $attendance->status === 'present' ? 'late' : 'present';
        $attendance->save();

        return redirect()->route('admin.dashboard')->with('success', 'Status absensi berhasil diubah');
    }

    public function deleteAttendance(Attendance $attendance)
    {
        // Hapus absensi
        $attendance->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Absensi berhasil dihapus');
    }
}
