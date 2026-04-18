<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use App\Models\SalaryComponent;
use App\Models\SalarySlip;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    // List all employees' salaries
    public function index()
    {
        $employees = User::with(['salary', 'salaryComponents'])->orderBy('name')->get();
        return view('salary.index', compact('employees'));
    }

    // Manage salary for employee
    public function manage(User $employee)
    {
        $salary     = $employee->salaries()->orderByDesc('effective_date')->first();
        $components = $employee->salaryComponents()->orderBy('type')->get();
        return view('salary.manage', compact('employee', 'salary', 'components'));
    }

    // Set basic salary
    public function setBasic(Request $request, User $employee)
    {
        $validated = $request->validate([
            'basic_salary'   => 'required|numeric|min:0',
            'effective_date' => 'required|date',
        ]);

        Salary::create([
            'user_id'        => $employee->id,
            'basic_salary'   => $validated['basic_salary'],
            'effective_date' => $validated['effective_date'],
        ]);

        return back()->with('success', 'Gaji pokok berhasil disimpan!');
    }

    // Add component (allowance / deduction)
    public function storeComponent(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'type'         => 'required|in:allowance,deduction',
            'amount'       => 'required|numeric|min:0',
            'is_recurring' => 'boolean',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');

        $employee->salaryComponents()->create($validated);

        return back()->with('success', 'Komponen gaji berhasil ditambahkan!');
    }

    public function destroyComponent(SalaryComponent $component)
    {
        $employee = $component->user;
        $component->delete();
        return back()->with('success', 'Komponen gaji berhasil dihapus!');
    }

    // Generate salary slip
    public function generateSlip(Request $request, User $employee)
    {
        $validated = $request->validate([
            'period_month' => 'required|integer|min:1|max:12',
            'period_year'  => 'required|integer|min:2000',
        ]);

        // Check if slip already exists
        $existingSlip = SalarySlip::where('user_id', $employee->id)
                                  ->where('period_month', $validated['period_month'])
                                  ->where('period_year', $validated['period_year'])
                                  ->first();

        if ($existingSlip) {
            return back()->with('error', 'Slip gaji untuk periode ini sudah ada!');
        }

        $salary     = $employee->salaries()->orderByDesc('effective_date')->first();
        if (!$salary) {
            return back()->with('error', 'Gaji pokok karyawan belum diatur!');
        }

        $components       = $employee->salaryComponents()->where('is_recurring', true)->get();
        $totalAllowances  = $components->where('type', 'allowance')->sum('amount');
        $totalDeductions  = $components->where('type', 'deduction')->sum('amount');
        $netSalary        = $salary->basic_salary + $totalAllowances - $totalDeductions;

        SalarySlip::create([
            'user_id'          => $employee->id,
            'period_month'     => $validated['period_month'],
            'period_year'      => $validated['period_year'],
            'basic_salary'     => $salary->basic_salary,
            'total_allowances' => $totalAllowances,
            'total_deductions' => $totalDeductions,
            'net_salary'       => $netSalary,
            'generated_at'     => now(),
        ]);

        return back()->with('success', 'Slip gaji berhasil di-generate!');
    }

    // Staff: view my slips
    public function mySlips(Request $request)
    {
        $user  = $request->user();
        $slips = $user->salarySlips()
                      ->orderByDesc('period_year')
                      ->orderByDesc('period_month')
                      ->paginate(12);

        return view('salary.my-slips', compact('slips'));
    }

    // View single slip
    public function showSlip(SalarySlip $slip)
    {
        // Staff can only view their own slips
        if (!auth()->user()->hasAnyRole(['admin', 'hrd']) && $slip->user_id !== auth()->id()) {
            abort(403);
        }

        $slip->load('user');
        $components = $slip->user->salaryComponents()->get();

        return view('salary.slip', compact('slip', 'components'));
    }

    // List all slips (admin/hrd)
    public function slips(Request $request)
    {
        $query = SalarySlip::with('user');

        if ($request->filled('month')) $query->where('period_month', $request->month);
        if ($request->filled('year'))  $query->where('period_year', $request->year);
        if ($request->filled('user_id')) $query->where('user_id', $request->user_id);

        $slips     = $query->orderByDesc('period_year')->orderByDesc('period_month')->paginate(20)->withQueryString();
        $employees = User::orderBy('name')->get();

        return view('salary.slips', compact('slips', 'employees'));
    }
}
