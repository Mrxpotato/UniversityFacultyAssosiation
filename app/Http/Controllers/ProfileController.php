<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $profile = $user->facultyProfile;

        $user->save();

        // Update faculty profile if exists
        if ($profile) {
            $profileData = $request->only([
                'surname',
                'first_name',
                'middle_name',
                'extension_name',
                'civil_status',
                'gender',
                'birth_date',
                'birth_place',
                'home_address',
                'address_province',
                'address_city',
                'address_barangay',
                'address_street',
                'phone_number',
            ]);

            // Handle ID picture upload
            if ($request->hasFile('id_picture')) {
                // Delete old picture if exists
                if ($profile->id_picture) {
                    Storage::disk('public')->delete($profile->id_picture);
                }
                $profileData['id_picture'] = $request->file('id_picture')->store('id-pictures', 'public');
            }

            // Update user's full name based on profile
            $middleName = $profileData['middle_name'] ?? null;
            $extensionName = $profileData['extension_name'] ?? null;

            $fullName = $profileData['first_name'].' '.
                       ($middleName ? substr($middleName, 0, 1).'. ' : '').
                       $profileData['surname'].
                       ($extensionName ? ' '.$extensionName : '');

            $user->update(['name' => $fullName]);

            $profile->update($profileData);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
