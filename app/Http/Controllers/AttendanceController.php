<?php
namespace App\Http\Controllers;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        // Cek apakah sudah pernah check-in hari ini
        $existingAttendance = Attendance::where('employee_id', Auth::id())
            ->whereDate('created_at', today())
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Anda sudah check-in hari ini');
        }

        $attendance = Attendance::create([
            'employee_id' => Auth::id(),
            'check_in' => Carbon::now('Asia/Jakarta'), 
            'status' => 'present'
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

        $attendance->update([
            'check_out' => Carbon::now('Asia/Jakarta')
        ]);

        return redirect()->back()->with('success', 'Check-out berhasil pada ' . $attendance->check_out->format('H:i:s'));
    }
}