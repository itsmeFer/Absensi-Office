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
    // Cek apakah karyawan sudah check-in hari ini
    $existingAttendance = Attendance::where('employee_id', Auth::id())
        ->whereDate('created_at', today())
        ->first();

    if ($existingAttendance) {
        return redirect()->back()->with('error', 'Anda sudah check-in hari ini');
    }

    // Tentukan waktu sekarang dan status berdasarkan batas waktu check-in
    $now = Carbon::now('Asia/Jakarta');
    $status = $now->format('H:i:s') > '08:00:00' ? 'late' : 'present';

    // Menyimpan foto check-in jika ada
    $checkInPhotoPath = null;
    if ($request->hasFile('check_in_photo')) {
        $checkInPhotoPath = $request->file('check_in_photo')->store('check-in-photos', 'public');
    }

    // Simpan data absensi
    Attendance::create([
        'employee_id' => Auth::id(),
        'check_in' => $now,
        'status' => $status,
        'check_in_location' => $request->input('check_in_location', null),
        'check_in_photo' => $checkInPhotoPath, // Menyimpan path foto
    ]);

    $message = $status === 'late' 
        ? 'Check-in berhasil pada ' . $now->format('H:i:s') . '. Anda terlambat!' 
        : 'Check-in berhasil pada ' . $now->format('H:i:s');
        
    return redirect()->back()->with('success', $message);
}


    public function checkOut(Request $request)
    {
        // Cek apakah karyawan sudah check-in
        $attendance = Attendance::where('employee_id', Auth::id())
            ->whereDate('created_at', today())
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'Anda belum check-in hari ini');
        }

        // Cek apakah karyawan sudah check-out
        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'Anda sudah check-out hari ini');
        }

        // Simpan waktu check-out dan lokasi
        $attendance->update([
            'check_out' => Carbon::now('Asia/Jakarta'),
            'check_out_location' => $request->input('check_out_location', null),
        ]);

        return redirect()->back()->with('success', 'Check-out berhasil pada ' . $attendance->check_out->format('H:i:s'));
    }
}
