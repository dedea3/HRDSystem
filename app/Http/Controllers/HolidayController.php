<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $holidays = Holiday::orderBy('date')->paginate(20);
        return view('holiday.index', compact('holidays'));
    }

    public function create()
    {
        return view('holiday.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'date'         => 'required|date',
            'type'         => 'required|in:national,company',
            'is_recurring' => 'boolean',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');
        Holiday::create($validated);

        return redirect()->route('holiday.index')
                         ->with('success', 'Hari libur berhasil ditambahkan!');
    }

    public function edit(Holiday $holiday)
    {
        return view('holiday.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'date'         => 'required|date',
            'type'         => 'required|in:national,company',
            'is_recurring' => 'boolean',
        ]);

        $validated['is_recurring'] = $request->has('is_recurring');
        $holiday->update($validated);

        return redirect()->route('holiday.index')
                         ->with('success', 'Hari libur berhasil diperbarui!');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holiday.index')
                         ->with('success', 'Hari libur berhasil dihapus!');
    }
}
