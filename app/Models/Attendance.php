<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    // Fungsi untuk handle check-in
    public function checkIn(Request $request)
    {
        $request->validate([
            'check_in_location' => 'required|string',
            'check_in_photo' => 'required|image|max:2048',
        ]);

        $user = Auth::user();

        // Cek apakah user sudah check-in hari ini
        $existingAttendance = Attendance::where('employee_id', $user->id)
            ->whereDate('created_at', today())
            ->first();

        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-in hari ini.');
        }

        // Upload foto selfie
        $path = $request->file('check_in_photo')->store('check_in_photos', 'public');

        // Simpan data check-in
        Attendance::create([
            'employee_id' => $user->id,
            'check_in' => now(),
            'check_in_location' => $request->check_in_location,
            'check_in_photo' => $path,
            'status' => 'check_in',
        ]);

        return redirect()->back()->with('success', 'Berhasil melakukan check-in.');
    }

    // Fungsi untuk handle check-out
    public function checkOut(Request $request)
    {
        $request->validate([
            'check_out_location' => 'required|string',
        ]);

        $user = Auth::user();

        // Cek apakah user sudah check-in hari ini
        $attendance = Attendance::where('employee_id', $user->id)
            ->whereDate('created_at', today())
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'Anda belum melakukan check-in hari ini.');
        }

        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        // Update data check-out
        $attendance->update([
            'check_out' => now(),
            'check_out_location' => $request->check_out_location,
            'status' => 'check_out',
        ]);

        return redirect()->back()->with('success', 'Berhasil melakukan check-out.');
    }
}
