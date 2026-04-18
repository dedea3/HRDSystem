<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Admin/HRD: list all attendances
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', today());
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->latest()->paginate(20)->withQueryString();
        $employees   = User::orderBy('name')->get();
        $date        = $request->date ?? today()->format('Y-m-d');

        return view('attendance.index', compact('attendances', 'employees', 'date'));
    }

    // Staff: my attendance
    public function myAttendance(Request $request)
    {
        $user  = $request->user();
        $month = $request->month ?? now()->month;
        $year  = $request->year  ?? now()->year;

        $attendances = $user->attendances()
                            ->whereMonth('date', $month)
                            ->whereYear('date', $year)
                            ->orderBy('date', 'desc')
                            ->get();

        $todayAttendance = $user->todayAttendance();

        $summary = [
            'present' => $attendances->where('status', 'present')->count(),
            'late'    => $attendances->where('status', 'late')->count(),
            'absent'  => $attendances->where('status', 'absent')->count(),
        ];

        return view('attendance.my', compact('attendances', 'todayAttendance', 'summary', 'month', 'year'));
    }

    // Staff: check in
    public function checkIn(Request $request)
    {
        $user  = $request->user();
        $today = today();

        if ($user->todayAttendance()) {
            return back()->with('error', 'Anda sudah melakukan check-in hari ini!');
        }

        $checkInTime = now();
        // If check in after 08:30 → late
        $threshold   = Carbon::parse($today->format('Y-m-d') . ' 08:30:00');
        $status      = $checkInTime->greaterThan($threshold) ? 'late' : 'present';

        Attendance::create([
            'user_id'  => $user->id,
            'date'     => $today,
            'check_in' => $checkInTime->format('H:i:s'),
            'status'   => $status,
            'notes'    => $request->notes,
        ]);

        $msg = $status === 'late'
            ? 'Check-in berhasil. Catatan: Anda terlambat.'
            : 'Check-in berhasil. Selamat bekerja!';

        return back()->with('success', $msg);
    }

    // Staff: check out
    public function checkOut(Request $request)
    {
        $user            = $request->user();
        $todayAttendance = $user->todayAttendance();

        if (!$todayAttendance) {
            return back()->with('error', 'Anda belum melakukan check-in hari ini!');
        }

        if ($todayAttendance->check_out) {
            return back()->with('error', 'Anda sudah melakukan check-out hari ini!');
        }

        $todayAttendance->update([
            'check_out' => now()->format('H:i:s'),
        ]);

        return back()->with('success', 'Check-out berhasil. Sampai jumpa besok!');
    }

    // Admin/HRD: edit attendance manually
    public function edit(Attendance $attendance)
    {
        $attendance->load('user');
        return view('attendance.edit', compact('attendance'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'check_in'  => 'required',
            'check_out' => 'nullable',
            'status'    => 'required|in:present,late,absent',
            'notes'     => 'nullable|string',
        ]);

        $attendance->update($validated);
        return redirect()->route('attendance.index')
                         ->with('success', 'Data absensi berhasil diperbarui!');
    }

    // Admin/HRD: create attendance record manually
    public function create()
    {
        $employees = User::orderBy('name')->get();
        return view('attendance.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => 'required|exists:users,id',
            'date'      => 'required|date',
            'check_in'  => 'required',
            'check_out' => 'nullable',
            'status'    => 'required|in:present,late,absent',
            'notes'     => 'nullable|string',
        ]);

        Attendance::updateOrCreate(
            ['user_id' => $validated['user_id'], 'date' => $validated['date']],
            $validated
        );

        return redirect()->route('attendance.index')
                         ->with('success', 'Data absensi berhasil disimpan!');
    }
}
