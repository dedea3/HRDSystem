<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\SalarySlip;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasAnyRole(['admin', 'hrd'])) {
            return $this->adminDashboard($user);
        }

        return $this->staffDashboard($user);
    }

    private function adminDashboard($user)
    {
        $totalEmployees   = User::count();
        $todayAttendances = Attendance::whereDate('date', today())->count();
        $pendingLeaves    = Leave::where('status', 'pending')->count();
        $thisMonthSlips   = SalarySlip::where('period_month', now()->month)
                                      ->where('period_year', now()->year)->count();

        $recentAttendances = Attendance::with('user')
                                ->whereDate('date', today())
                                ->latest()
                                ->limit(10)
                                ->get();

        $pendingLeaveList = Leave::with('user')
                                ->where('status', 'pending')
                                ->latest()
                                ->limit(5)
                                ->get();

        // Last 7 days attendance chart
        $attendanceChart = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $attendanceChart->push([
                'date'    => $date->format('d M'),
                'present' => Attendance::whereDate('date', $date)->where('status', 'present')->count(),
                'late'    => Attendance::whereDate('date', $date)->where('status', 'late')->count(),
                'absent'  => Attendance::whereDate('date', $date)->where('status', 'absent')->count(),
            ]);
        }

        return view('dashboard', compact(
            'totalEmployees', 'todayAttendances', 'pendingLeaves',
            'thisMonthSlips', 'recentAttendances', 'pendingLeaveList', 'attendanceChart'
        ));
    }

    private function staffDashboard($user)
    {
        $todayAttendance  = $user->todayAttendance();
        $leaveBalance     = $user->currentLeaveBalance();
        $recentLeaves     = $user->leaves()->latest()->limit(5)->get();
        $recentAttendance = $user->attendances()->latest()->limit(7)->get();
        $recentSlips      = $user->salarySlips()->orderByDesc('period_year')
                                 ->orderByDesc('period_month')
                                 ->limit(3)->get();

        return view('dashboard', compact(
            'todayAttendance', 'leaveBalance',
            'recentLeaves', 'recentAttendance', 'recentSlips'
        ));
    }
}
