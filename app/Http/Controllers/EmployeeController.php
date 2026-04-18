<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        $employees = $query->orderBy('name')->paginate(15)->withQueryString();
        $roles     = Role::all();

        return view('employees.index', compact('employees', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('employees.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)],
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,id',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->roles()->attach($validated['roles']);

        // Create leave balance for current year
        LeaveBalance::create([
            'user_id'        => $user->id,
            'year'           => now()->year,
            'total_days'     => 12,
            'used_days'      => 0,
            'remaining_days' => 12,
        ]);

        return redirect()->route('employees.index')
                         ->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function show(User $employee)
    {
        $employee->load(['roles', 'attendances' => fn($q) => $q->latest()->limit(10),
                         'leaves' => fn($q) => $q->latest()->limit(5),
                         'salarySlips' => fn($q) => $q->orderByDesc('period_year')->orderByDesc('period_month')->limit(6)]);
        $leaveBalance = $employee->currentLeaveBalance();
        $salary       = $employee->salary;

        return view('employees.show', compact('employee', 'leaveBalance', 'salary'));
    }

    public function edit(User $employee)
    {
        $employee->load('roles');
        $roles = Role::all();
        return view('employees.edit', compact('employee', 'roles'));
    }

    public function update(Request $request, User $employee)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $employee->id,
            'password' => ['nullable', Password::min(8)],
            'roles'    => 'required|array|min:1',
            'roles.*'  => 'exists:roles,id',
        ]);

        $employee->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
            ...($validated['password'] ? ['password' => Hash::make($validated['password'])] : []),
        ]);

        $employee->roles()->sync($validated['roles']);

        return redirect()->route('employees.index')
                         ->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(User $employee)
    {
        if ($employee->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        $employee->delete();
        return redirect()->route('employees.index')
                         ->with('success', 'Karyawan berhasil dihapus!');
    }
}
