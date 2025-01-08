<?php

namespace App\Http\Controllers\Admin;
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;  // Menggunakan model User untuk mengambil data karyawan
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil data karyawan dari tabel users
        $users = User::all();
        $totalUsers = $users->count();  // Hitung jumlah karyawan
        $todayAttendance = Attendance::with('employee')
            ->whereDate('check_in', today())
            ->get();

        return view('admin.dashboard', compact('users', 'totalUsers', 'todayAttendance'));
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
