<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\SalaryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // ─── Dashboard ────────────────────────────────────────────────
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Profile (Breeze) ─────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ─── Employee Management (Admin, HRD) ─────────────────────────
    Route::middleware('role:admin,hrd')->group(function () {
        Route::resource('employees', EmployeeController::class);
    });

    // ─── Attendance ───────────────────────────────────────────────

    // Staff: own attendance
    Route::get('/my-attendance', [AttendanceController::class, 'myAttendance'])->name('attendance.my');
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');

    // Admin/HRD: manage
    Route::middleware('role:admin,hrd')->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
        Route::get('/attendance/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
        Route::patch('/attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
    });

    // ─── Leave ────────────────────────────────────────────────────

    // Staff: own leaves
    Route::get('/my-leaves', [LeaveController::class, 'myLeaves'])->name('leave.my');
    Route::get('/leave/create', [LeaveController::class, 'create'])->name('leave.create');
    Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
    Route::delete('/leave/{leave}', [LeaveController::class, 'destroy'])->name('leave.destroy');

    // Admin/HRD: manage
    Route::middleware('role:admin,hrd')->group(function () {
        Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index');
        Route::get('/leave/{leave}', [LeaveController::class, 'show'])->name('leave.show');
        Route::patch('/leave/{leave}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
        Route::patch('/leave/{leave}/reject', [LeaveController::class, 'reject'])->name('leave.reject');
    });

    // ─── Holiday (Admin, HRD) ─────────────────────────────────────
    Route::middleware('role:admin,hrd')->group(function () {
        Route::resource('holiday', HolidayController::class)->except(['show']);
    });

    // ─── Salary & Payroll (Admin, HRD) ────────────────────────────
    Route::middleware('role:admin,hrd')->group(function () {
        Route::get('/salary', [SalaryController::class, 'index'])->name('salary.index');
        Route::get('/salary/{employee}/manage', [SalaryController::class, 'manage'])->name('salary.manage');
        Route::post('/salary/{employee}/basic', [SalaryController::class, 'setBasic'])->name('salary.set-basic');
        Route::post('/salary/{employee}/component', [SalaryController::class, 'storeComponent'])->name('salary.add-component');
        Route::delete('/salary/component/{component}', [SalaryController::class, 'destroyComponent'])->name('salary.delete-component');
        Route::post('/salary/{employee}/generate', [SalaryController::class, 'generateSlip'])->name('salary.generate');
        Route::get('/salary/slips/all', [SalaryController::class, 'slips'])->name('salary.slips');
    });

    // Staff: my salary slips
    Route::get('/my-salary', [SalaryController::class, 'mySlips'])->name('salary.my');
    Route::get('/salary/slip/{slip}', [SalaryController::class, 'showSlip'])->name('salary.slip');

});

require __DIR__.'/auth.php';
