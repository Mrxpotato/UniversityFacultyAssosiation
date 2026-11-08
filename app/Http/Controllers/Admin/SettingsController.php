<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     */
    public function index(): View
    {
        $registrationEnabled = SystemSetting::isRegistrationEnabled();
        $processesEnabled = SystemSetting::areProcessesEnabled();
        $currentTheme = SystemSetting::getColorTheme();
        $availableThemes = config('themes.themes', []);

        return view('admin.settings.index', compact('registrationEnabled', 'processesEnabled', 'currentTheme', 'availableThemes'));
    }

    /**
     * Toggle registration setting
     */
    public function toggleRegistration(): RedirectResponse
    {
        $newValue = SystemSetting::toggleRegistration();

        $status = $newValue ? 'enabled' : 'disabled';

        return back()->with('success', "Member registration has been {$status}.");
    }

    /**
     * Toggle processes setting
     */
    public function toggleProcesses(): RedirectResponse
    {
        $newValue = SystemSetting::toggleProcesses();

        $status = $newValue ? 'enabled' : 'disabled';
        $mode = $newValue ? 'Full access mode' : 'Read-only mode';

        return back()->with('success', "System processes have been {$status}. {$mode} is now active.");
    }

    /**
     * Update general settings
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'registration_enabled' => ['sometimes', 'boolean'],
        ]);

        if (isset($validated['registration_enabled'])) {
            SystemSetting::set(
                'registration_enabled',
                $validated['registration_enabled'],
                'boolean',
                'registration',
                'Enable or disable public member registration'
            );
        }

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Update the system color theme
     */
    public function updateTheme(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'color_theme' => ['required', 'string', 'in:'.implode(',', array_keys(config('themes.themes', [])))],
        ]);

        SystemSetting::setColorTheme($validated['color_theme']);

        $themeName = config("themes.themes.{$validated['color_theme']}.name", $validated['color_theme']);

        return back()->with('success', "Color theme changed to {$themeName}.");
    }
}
