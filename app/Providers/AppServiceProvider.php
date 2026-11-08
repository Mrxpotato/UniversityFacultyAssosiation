<?php

namespace App\Providers;

use App\Models\Beneficiary;
use App\Models\Campus;
use App\Models\Claim;
use App\Models\FacultyProfile;
use App\Models\SystemSetting;
use App\Models\TrainingSeminar;
use App\Models\TransparencyPost;
use App\Policies\BeneficiaryPolicy;
use App\Policies\CampusPolicy;
use App\Policies\ClaimPolicy;
use App\Policies\FacultyProfilePolicy;
use App\Policies\TrainingSeminarPolicy;
use App\Policies\TransparencyPostPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share active color theme with all views
        View::composer('*', function ($view) {
            static $themeData = null;
            if ($themeData === null) {
                try {
                    $themeKey = SystemSetting::getColorTheme();
                } catch (\Throwable $e) {
                    $themeKey = config('themes.default', 'crimson');
                }
                $themeConfig = config("themes.themes.{$themeKey}") ?? config('themes.themes.crimson');
                $themeData = [
                    'themeKey' => $themeKey,
                    'themeColors' => $themeConfig['colors'] ?? [],
                    'themeSidebar' => $themeConfig['sidebar'] ?? ['#2d0000', '#450000', '#780000'],
                    'themeSelection' => $themeConfig['selection'] ?? 'rgba(120, 0, 0, 0.85)',
                ];
            }
            $view->with($themeData);
        });

        // Register policies
        Gate::policy(FacultyProfile::class, FacultyProfilePolicy::class);
        Gate::policy(Beneficiary::class, BeneficiaryPolicy::class);
        Gate::policy(Claim::class, ClaimPolicy::class);
        Gate::policy(TransparencyPost::class, TransparencyPostPolicy::class);
        Gate::policy(TrainingSeminar::class, TrainingSeminarPolicy::class);
        Gate::policy(Campus::class, CampusPolicy::class);

        // Define gates for admin access (super admin only)
        Gate::define('admin', function ($user) {
            return $user->isSuperAdmin();
        });

        // University President can manage (approve/reject/remand) claims
        Gate::define('manage-claims', function ($user) {
            return $user->canManageClaims();
        });

        // University Treasurer can release claims
        Gate::define('release-claims', function ($user) {
            return $user->canReleaseClaims();
        });

        // University Treasurer can manage dues (generate, mark paid, export)
        Gate::define('manage-dues', function ($user) {
            return $user->canManageDues();
        });

        // Any officer can view reports
        Gate::define('view-reports', function ($user) {
            return $user->canViewReports();
        });

        // Only super admin can manage members across all campuses
        Gate::define('manage-members', function ($user) {
            return $user->isSuperAdmin();
        });

        // Officer gate — any officer role
        Gate::define('officer', function ($user) {
            return $user->isOfficer();
        });

        // Campus president or above can manage pending registrations
        Gate::define('manage-registrations', function ($user) {
            return $user->canManagePendingRegistrations();
        });
    }
}
