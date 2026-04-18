<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveBalance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    // Admin/HRD: all leaves
    public function index(Request $request)
    {
        $query = Leave::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $leaves    = $query->latest()->paginate(15)->withQueryString();
        $employees = User::orderBy('name')->get();

        return view('leave.index', compact('leaves', 'employees'));
    }

    // Staff: my leaves
    public function myLeaves(Request $request)
    {
        $user         = $request->user();
        $leaves       = $user->leaves()->latest()->paginate(10);
        $leaveBalance = $user->currentLeaveBalance();

        return view('leave.my', compact('leaves', 'leaveBalance'));
    }

    public function create()
    {
        return view('leave.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|in:annual,sick,emergency',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:1000',
        ]);

        $user      = $request->user();
        $startDate = Carbon::parse($validated['start_date']);
        $endDate   = Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInWeekdays($endDate) + 1;

        // Check annual leave balance
        if ($validated['leave_type'] === 'annual') {
            $balance = $user->currentLeaveBalance();
            if (!$balance || $balance->remaining_days < $totalDays) {
                return back()->withErrors(['leave_type' => 'Saldo cuti tahunan Anda tidak mencukupi!'])->withInput();
            }
        }

        Leave::create([
            'user_id'    => $user->id,
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'],
            'total_days' => $totalDays,
            'reason'     => $validated['reason'],
            'status'     => 'pending',
        ]);

        return redirect()->route('leave.my')
                         ->with('success', 'Pengajuan cuti berhasil dikirim! Menunggu persetujuan.');
    }

    public function show(Leave $leave)
    {
        $leave->load(['user', 'approver']);
        return view('leave.show', compact('leave'));
    }

    // HRD/Admin: approve
    public function approve(Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses!');
        }

        $leave->update([
            'status'      => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Deduct annual leave balance
        if ($leave->leave_type === 'annual') {
            $balance = LeaveBalance::where('user_id', $leave->user_id)
                                   ->where('year', now()->year)
                                   ->first();
            if ($balance) {
                $balance->update([
                    'used_days'      => $balance->used_days + $leave->total_days,
                    'remaining_days' => $balance->remaining_days - $leave->total_days,
                ]);
            }
        }

        return back()->with('success', 'Pengajuan cuti telah disetujui!');
    }

    // HRD/Admin: reject
    public function reject(Request $request, Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses!');
        }

        $leave->update([
            'status'      => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Pengajuan cuti telah ditolak.');
    }

    public function destroy(Leave $leave)
    {
        if ($leave->user_id !== auth()->id() || $leave->status !== 'pending') {
            return back()->with('error', 'Tidak dapat menghapus pengajuan ini!');
        }
        $leave->delete();
        return back()->with('success', 'Pengajuan cuti berhasil dibatalkan.');
    }
}
