<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\FacultyProfile;
use App\Models\PendingRegistration;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PendingRegistrationController extends Controller
{
    /**
     * Display pending registrations for the campus president's campus
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // Determine scope based on role
        $isCampusPresident = $user->isCampusPresident();
        $isAdmin = $user->isSuperAdmin() || $user->isUniversityPresident();

        $query = PendingRegistration::with('campus');

        // Campus presidents only see their campus registrations
        if ($isCampusPresident && ! $isAdmin) {
            $query->where('campus_id', $user->campus_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending'); // Default to pending
        }

        // Filter by campus (for admin/university president)
        if ($isAdmin && $request->filled('campus_id')) {
            $query->where('campus_id', $request->campus_id);
        }

        $registrations = $query->latest()->paginate(10);
        $campuses = Campus::where('is_active', true)->orderBy('name')->get();

        return view('admin.pending-registrations.index', compact('registrations', 'campuses', 'isCampusPresident', 'isAdmin'));
    }

    /**
     * Show a single pending registration
     */
    public function show(PendingRegistration $pendingRegistration): View
    {
        $user = Auth::user();

        $isCampusPresident = $user->isCampusPresident();
        $isAdmin = $user->isSuperAdmin() || $user->isUniversityPresident();

        // Campus presidents can only view their own campus registrations
        if ($isCampusPresident && ! $isAdmin && $pendingRegistration->campus_id !== $user->campus_id) {
            abort(403, 'You can only view registrations from your campus.');
        }

        $registration = $pendingRegistration;

        // Check if employee ID is already in use
        $employeeIdExists = User::where('employee_id', $pendingRegistration->employee_id_number)->exists()
            || FacultyProfile::where('employee_id', $pendingRegistration->employee_id_number)->exists();

        return view('admin.pending-registrations.show', compact('registration', 'pendingRegistration', 'isCampusPresident', 'isAdmin', 'employeeIdExists'));
    }

    /**
     * Approve a pending registration
     */
    public function approve(PendingRegistration $pendingRegistration): RedirectResponse
    {
        $user = Auth::user();

        $isCampusPresident = $user->isCampusPresident();
        $isAdmin = $user->isSuperAdmin() || $user->isUniversityPresident();

        // Campus presidents can only approve their own campus registrations
        if ($isCampusPresident && ! $isAdmin && $pendingRegistration->campus_id !== $user->campus_id) {
            abort(403, 'You can only approve registrations from your campus.');
        }

        if ($pendingRegistration->status !== 'pending') {
            return back()->with('error', 'This registration has already been processed.');
        }

        // Check if employee ID is already in use
        $employeeIdExists = User::where('employee_id', $pendingRegistration->employee_id_number)->exists()
            || FacultyProfile::where('employee_id', $pendingRegistration->employee_id_number)->exists();

        if ($employeeIdExists) {
            return back()->with('error', 'Cannot approve: Employee ID "'.$pendingRegistration->employee_id_number.'" is already in use by another member.');
        }

        // Create the user account (admin-approved)
        $newUser = User::create([
            'name' => $pendingRegistration->full_name,
            'employee_id' => $pendingRegistration->employee_id_number,
            'password' => $pendingRegistration->password, // Already hashed
            'role' => 'faculty',
            'campus_id' => $pendingRegistration->campus_id,
        ]);

        // Move files from pending folder to permanent folder
        $idPicturePath = null;
        if ($pendingRegistration->id_picture) {
            $newPath = str_replace('pending-registrations/', '', $pendingRegistration->id_picture);
            Storage::disk('public')->move($pendingRegistration->id_picture, $newPath);
            $idPicturePath = $newPath;
        }

        // Create faculty profile
        FacultyProfile::create([
            'user_id' => $newUser->id,
            'prefix' => $pendingRegistration->prefix,
            'surname' => $pendingRegistration->surname,
            'first_name' => $pendingRegistration->first_name,
            'middle_name' => $pendingRegistration->middle_name,
            'extension_name' => $pendingRegistration->extension_name,
            'civil_status' => $pendingRegistration->civil_status,
            'gender' => $pendingRegistration->gender,
            'birth_date' => $pendingRegistration->birth_date,
            'birth_place' => $pendingRegistration->birth_place,
            'home_address' => $pendingRegistration->home_address,
            'address_province' => $pendingRegistration->address_province,
            'address_city' => $pendingRegistration->address_city,
            'address_barangay' => $pendingRegistration->address_barangay,
            'address_street' => $pendingRegistration->address_street,
            'phone_number' => $pendingRegistration->phone_number,
            'id_picture' => $idPicturePath,
            'employee_id' => $pendingRegistration->employee_id_number,
            'campus_id' => $pendingRegistration->campus_id,
            'membership_status' => 'active',
            'membership_date' => now(),
        ]);

        // Update pending registration status
        $pendingRegistration->update([
            'status' => 'approved',
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
        ]);

        // Fire registered event for email verification if needed
        event(new Registered($newUser));

        // TODO: Send approval email to the user

        return redirect()->route('admin.pending-registrations.index')
            ->with('success', "Registration for {$pendingRegistration->full_name} has been approved successfully!");
    }

    /**
     * Reject a pending registration
     */
    public function reject(Request $request, PendingRegistration $pendingRegistration): RedirectResponse
    {
        $user = Auth::user();

        $isCampusPresident = $user->isCampusPresident();
        $isAdmin = $user->isSuperAdmin() || $user->isUniversityPresident();

        // Campus presidents can only reject their own campus registrations
        if ($isCampusPresident && ! $isAdmin && $pendingRegistration->campus_id !== $user->campus_id) {
            abort(403, 'You can only reject registrations from your campus.');
        }

        if ($pendingRegistration->status !== 'pending') {
            return back()->with('error', 'This registration has already been processed.');
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $pendingRegistration->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
        ]);

        // TODO: Send rejection email to the user

        return redirect()->route('admin.pending-registrations.index')
            ->with('success', "Registration for {$pendingRegistration->full_name} has been rejected.");
    }
}
