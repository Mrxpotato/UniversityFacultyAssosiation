<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\PendingRegistration;
use App\Rules\CloudflareTurnstile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $campuses = Campus::where('is_active', true)->orderBy('name')->get();

        return view('auth.register', compact('campuses'));
    }

    /**
     * Handle an incoming registration request.
     * Creates a pending registration that must be approved by campus president.
     *
     * @throws \Illuminate\Validation\ValidationException
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
            'gender' => ['required', 'in:male,female'],
            'birth_date' => ['required', 'date', 'before:today'],
            'birth_place' => ['required', 'string', 'max:255'],
            'home_address' => ['required', 'string'],
            'address_province' => ['required', 'string', 'max:100'],
            'address_city' => ['required', 'string', 'max:100'],
            'address_barangay' => ['required', 'string', 'max:100'],
            'address_street' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],

            // Campus & Employee Info
            'campus_id' => ['required', 'exists:campuses,id'],

            // File Uploads
            'id_picture' => ['required', 'image', 'max:2048'], // 2MB max
            'employee_id_front' => ['required', 'image', 'max:2048'],
            'employee_id_back' => ['required', 'image', 'max:2048'],

            // Account Information
            'employee_id' => ['required', 'string', 'max:50', 'unique:users,employee_id', 'unique:pending_registrations,employee_id_number'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ] + (config('services.cloudflare.turnstile_secret')
            ? ['cf-turnstile-response' => ['required', new CloudflareTurnstile]]
            : [])
        );

        // Handle file uploads
        $idPicturePath = $request->file('id_picture')->store('pending-registrations/id-pictures', 'public');
        $employeeIdFrontPath = $request->file('employee_id_front')->store('pending-registrations/employee-ids', 'public');
        $employeeIdBackPath = $request->file('employee_id_back')->store('pending-registrations/employee-ids', 'public');

        // Create pending registration
        PendingRegistration::create([
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
            'address_province' => $validated['address_province'],
            'address_city' => $validated['address_city'],
            'address_barangay' => $validated['address_barangay'],
            'address_street' => $validated['address_street'] ?? null,
            'phone_number' => $validated['phone_number'],
            'campus_id' => $validated['campus_id'],
            'employee_id_number' => $validated['employee_id'],
            'id_picture' => $idPicturePath,
            'employee_id_front' => $employeeIdFrontPath,
            'employee_id_back' => $employeeIdBackPath,
            'password' => $validated['password'],
            'status' => 'pending',
        ]);

        return redirect()->route('login')
            ->with('success', 'Your registration has been submitted successfully! Please wait for your Campus President to review and approve your application.');
    }
}
