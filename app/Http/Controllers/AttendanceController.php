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
        // Validasi data check-in
        $request->validate([
            'check_in_location' => 'required|string',
            'check_in_photo' => 'nullable|image|max:2048',
        ]);
    
        // Cek apakah karyawan sudah check-in hari ini
        $existingAttendance = Attendance::where('employee_id', Auth::id())
            ->whereDate('created_at', today())
            ->first();
    
        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Anda sudah check-in hari ini');
        }
    
        // Waktu check-in
        $now = Carbon::now('Asia/Jakarta');
        $status = $now->format('H:i:s') > '08:00:00' ? 'late' : 'present';
    
        // Upload foto check-in jika ada
        $checkInPhotoPath = null;
        if ($request->hasFile('check_in_photo')) {
            $checkInPhotoPath = $request->file('check_in_photo')->store('check-in-photos', 'public');
        }
    
        // Simpan data absensi
        Attendance::create([
            'employee_id' => Auth::id(), // Mengisi employee_id
            'check_in' => $now,         // Mengisi waktu check-in
            'status' => $status,        // Mengisi status kehadiran
            'check_in_location' => $request->input('check_in_location'), // Mengisi lokasi check-in
            'check_in_photo' => $checkInPhotoPath,                       // Menyimpan foto check-in
        ]);
    
        $message = $status === 'late' 
            ? 'Check-in berhasil pada ' . $now->format('H:i:s') . '. Anda terlambat!' 
            : 'Check-in berhasil pada ' . $now->format('H:i:s');
    
        return redirect()->back()->with('success', $message);
    }
    


    public function checkOut(Request $request)
    {
        // Validasi data check-out
        $request->validate([
            'check_out_location' => 'required|string',
        ]);
    
        // Ambil data absensi hari ini berdasarkan employee_id
        $attendance = Attendance::where('employee_id', Auth::id())
            ->whereDate('created_at', today())
            ->first();
    
        if (!$attendance) {
            return redirect()->back()->with('error', 'Anda belum check-in hari ini');
        }
    
        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'Anda sudah check-out hari ini');
        }
    
        // Simpan waktu check-out dan lokasi
        $attendance->update([
            'check_out' => Carbon::now('Asia/Jakarta'),
            'check_out_location' => $request->input('check_out_location'),
        ]);
    
        return redirect()->back()->with('success', 'Check-out berhasil pada ' . $attendance->check_out->format('H:i:s'));
    }
    
}
