<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\College;
use Illuminate\Http\Request;

class CollegeController extends Controller
{
    /**
     * Display colleges for a specific campus.
     */
    public function index(Campus $campus)
    {
        $colleges = College::where('campus_id', $campus->id)
            ->withCount('facultyProfiles')
            ->orderBy('name')
            ->get();

        return view('admin.campuses.colleges.index', compact('campus', 'colleges'));
    }

    /**
     * Show the form for creating a new college.
     */
    public function create(Campus $campus)
    {
        return view('admin.campuses.colleges.create', compact('campus'));
    }

    /**
     * Store a newly created college.
     */
    public function store(Request $request, Campus $campus)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['campus_id'] = $campus->id;
        $validated['is_active'] = $request->boolean('is_active', true);

        College::create($validated);

        return redirect()
            ->route('admin.campuses.colleges', $campus)
            ->with('success', 'College created successfully.');
    }

    /**
     * Show the form for editing a college.
     */
    public function edit(Campus $campus, College $college)
    {
        // Ensure college belongs to this campus
        if ($college->campus_id !== $campus->id) {
            abort(404);
        }

        return view('admin.campuses.colleges.edit', compact('campus', 'college'));
    }

    /**
     * Update the specified college.
     */
    public function update(Request $request, Campus $campus, College $college)
    {
        // Ensure college belongs to this campus
        if ($college->campus_id !== $campus->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $college->update($validated);

        return redirect()
            ->route('admin.campuses.colleges', $campus)
            ->with('success', 'College updated successfully.');
    }

    /**
     * Remove the specified college.
     */
    public function destroy(Campus $campus, College $college)
    {
        // Ensure college belongs to this campus
        if ($college->campus_id !== $campus->id) {
            abort(404);
        }

        // Check if college has faculty profiles
        if ($college->facultyProfiles()->exists()) {
            return redirect()
                ->route('admin.campuses.colleges', $campus)
                ->with('error', 'Cannot delete college with existing faculty members.');
        }

        $college->delete();

        return redirect()
            ->route('admin.campuses.colleges', $campus)
            ->with('success', 'College deleted successfully.');
    }
}
