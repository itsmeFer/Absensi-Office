<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{

    public function showPreviousAttendance()
    {
        $yesterday = Carbon::yesterday()->toDateString();

        $attendances = Attendance::whereDate('created_at', $yesterday)->get();

        return view('admin.attendance.previous', compact('attendances', 'yesterday'));
    }
    public function checkIn(Request $request)
    {
        // Cek apakah sudah pernah check-in hari ini
        $existingAttendance = Attendance::where('employee_id', Auth::id())
            ->whereDate('created_at', today())
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Anda sudah check-in hari ini');
        }

        // Set status based on time
        $now = Carbon::now('Asia/Jakarta');
        $status = $now->format('H:i:s') > '08:00:00' ? 'late' : 'present';

        // Menyimpan lokasi check-in
        $attendance = Attendance::create([
            'employee_id' => Auth::id(),
            'check_in' => $now,
            'status' => $status,
            'check_in_location' => $request->input('check_in_location', null), // Ambil lokasi dari request
        ]);

        return redirect()->back()->with('success', 'Check-in berhasil pada ' . $attendance->check_in->format('H:i:s'));
    }

    public function checkOut(Request $request)
    {
        $attendance = Attendance::where('employee_id', Auth::id())
            ->whereDate('created_at', today())
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'Anda belum check-in hari ini');
        }

        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'Anda sudah check-out hari ini');
        }

        // Menyimpan lokasi check-out
        $attendance->update([
            'check_out' => Carbon::now('Asia/Jakarta'),
            'check_out_location' => $request->input('check_out_location', null), // Ambil lokasi check-out dari request
        ]);

        return redirect()->back()->with('success', 'Check-out berhasil pada ' . $attendance->check_out->format('H:i:s'));
    }
}
