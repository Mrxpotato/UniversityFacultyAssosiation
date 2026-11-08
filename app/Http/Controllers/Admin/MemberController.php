<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicRank;
use App\Models\Campus;
use App\Models\College;
use App\Models\FacultyProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class MemberController extends Controller
{
    /**
     * Show the form for creating a new member
     */
    public function create(): View
    {
        $user = auth()->user();

        // Campus presidents can only create members for their campus
        if ($user->isCampusPresident()) {
            $campuses = Campus::active()->where('id', $user->campus_id)->get();
            $colleges = College::where('campus_id', $user->campus_id)->orderBy('name')->get();
        } else {
            $campuses = Campus::active()->orderBy('name')->get();
            $colleges = College::orderBy('name')->get();
        }

        $academicRanks = AcademicRank::active()->ordered()->get();

        return view('admin.members.create', compact('campuses', 'colleges', 'academicRanks'));
    }

    /**
     * Store a newly created member
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Personal Information
            'prefix' => ['nullable', 'string', 'in:Dr.,Prof.,Engr.,Atty.,Mr.,Ms.,Mrs.'],
            'surname' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'extension_name' => ['nullable', 'string', 'max:10'],
            'civil_status' => ['required', 'in:single,married,widowed,separated'],
            'gender' => ['required', 'in:male,female,lgbtqia+'],
            'birth_date' => ['required', 'date', 'before:today'],
            'birth_place' => ['required', 'string', 'max:255'],
            'home_address' => ['required', 'string'],
            'address_province' => ['nullable', 'string', 'max:100'],
            'address_city' => ['nullable', 'string', 'max:100'],
            'address_barangay' => ['nullable', 'string', 'max:100'],
            'address_street' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'id_picture' => ['nullable', 'image', 'max:2048'],

            // Account Information
            'employee_id' => ['required', 'string', 'max:50', 'unique:users,employee_id', 'unique:faculty_profiles,employee_id'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:faculty,campus_president,university_president,university_treasurer,super_admin'],
            'campus_id' => ['required', 'exists:campuses,id'],
            'college_id' => ['nullable', 'exists:colleges,id'],
            'academic_rank_id' => ['nullable', 'exists:academic_ranks,id'],
            'date_of_service' => ['nullable', 'date'],
            'membership_status' => ['required', 'in:active,inactive,retired'],
            'membership_date' => ['nullable', 'date'],
            'officer_position' => ['nullable', 'string', 'in:President,Vice President,Secretary,Treasurer,Assistant Treasurer,Auditor,Business Manager,PIO'],
            'campus_position' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        // Campus presidents can only create faculty in their campus.
        // Only super admins may assign officer/system roles (prevents privilege escalation).
        $user = auth()->user();
        if ($user->isCampusPresident()) {
            $validated['campus_id'] = $user->campus_id;
            $validated['role'] = 'faculty';
        } elseif (! $user->isSuperAdmin()) {
            $validated['role'] = 'faculty';
        }

        // Create user account with full name (including prefix)
        $fullName = ($validated['prefix'] ?? '').
                   ($validated['prefix'] ? ' ' : '').
                   $validated['first_name'].' '.
                   ($validated['middle_name'] ? substr($validated['middle_name'], 0, 1).'. ' : '').
                   $validated['surname'].
                   ($validated['extension_name'] ? ' '.$validated['extension_name'] : '');

        $user = User::create([
            'name' => $fullName,
            'employee_id' => $validated['employee_id'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'campus_id' => $validated['campus_id'],
            'is_active' => true,
        ]);

        // Handle ID picture upload
        $idPicturePath = null;
        if ($request->hasFile('id_picture')) {
            $idPicturePath = $request->file('id_picture')->store('id-pictures', 'public');
        }

        // Create faculty profile with all information
        FacultyProfile::create([
            'user_id' => $user->id,
            'prefix' => $validated['prefix'] ?? null,
            'surname' => $validated['surname'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'extension_name' => $validated['extension_name'] ?? null,
            'civil_status' => $validated['civil_status'],
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'],
            'birth_place' => $validated['birth_place'],
            'home_address' => $validated['home_address'],
            'address_province' => $validated['address_province'] ?? null,
            'address_city' => $validated['address_city'] ?? null,
            'address_barangay' => $validated['address_barangay'] ?? null,
            'address_street' => $validated['address_street'] ?? null,
            'phone_number' => $validated['phone_number'],
            'id_picture' => $idPicturePath,
            'employee_id' => $validated['employee_id'],
            'campus_id' => $validated['campus_id'],
            'college_id' => $validated['college_id'] ?? null,
            'academic_rank_id' => $validated['academic_rank_id'] ?? null,
            'date_of_service' => $validated['date_of_service'] ?? null,
            'membership_status' => $validated['membership_status'],
            'membership_date' => $validated['membership_date'] ?? now(),
            'officer_position' => $validated['officer_position'] ?? null,
            'campus_position' => $validated['campus_position'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        event(new Registered($user));

        return redirect()->route('admin.faculty-profiles.index')
            ->with('success', 'New member has been registered successfully.');
    }

    /**
     * Get colleges for a campus (AJAX)
     */
    public function getColleges(Campus $campus)
    {
        return response()->json(
            $campus->colleges()->orderBy('name')->get(['id', 'name', 'code'])
        );
    }
}
