<?php

use App\Http\Controllers\Admin\ClaimTypeController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\DeductionSettingController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MemoController;
use App\Http\Controllers\Admin\OfficerManagementController;
use App\Http\Controllers\Admin\ReleaseController;
use App\Http\Controllers\Admin\ResolutionController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TrainingAnnouncementController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DueController;
use App\Http\Controllers\FacultyProfileController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\MonthlyDeductionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PendingRegistrationController;
use App\Http\Controllers\PhAddressController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalaryDeductionController;
use App\Http\Controllers\TrainingSeminarController;
use App\Http\Controllers\TransparencyPostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PageController::class, 'home'])->name('home');

// Transparency Wall (Public)
Route::get('/transparency', [TransparencyPostController::class, 'index'])->name('transparency.index');
Route::get('/transparency/{transparencyPost:slug}', [TransparencyPostController::class, 'show'])->name('transparency.show');

// Trainings & Seminars (Public)
Route::get('/trainings', [TrainingSeminarController::class, 'index'])->name('trainings.index');
Route::get('/trainings/calendar', [TrainingSeminarController::class, 'calendar'])->name('trainings.calendar');
Route::get('/trainings/{trainingSeminar:slug}', [TrainingSeminarController::class, 'show'])->name('trainings.show');

// Campuses (Public View)
Route::get('/campuses', [CampusController::class, 'index'])->name('campuses.index');
Route::get('/campuses/{campus}', [CampusController::class, 'show'])->name('campuses.show');

// Organization Chart (Public View)
Route::get('/organization', [PageController::class, 'organization'])->name('organization');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Profile (Breeze) - View only
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // My Faculty Profile - View, Edit, Update (always available)
    Route::get('/my-profile', [FacultyProfileController::class, 'myProfile'])->name('my-profile');
    Route::get('/my-profile/edit', [FacultyProfileController::class, 'editMyProfile'])->name('my-profile.edit');
    Route::put('/my-profile', [FacultyProfileController::class, 'updateMyProfile'])->name('my-profile.update');

    // API: Get colleges for a campus (used in profile edit)
    Route::get('/api/colleges/{campus}', function (\App\Models\Campus $campus) {
        return response()->json(
            $campus->colleges()->active()->orderBy('name')->get(['id', 'name', 'code'])
        );
    })->name('api.colleges');

    // Beneficiaries - View only
    Route::get('/beneficiaries', [BeneficiaryController::class, 'index'])->name('beneficiaries.index');
    Route::get('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'show'])->name('beneficiaries.show')->whereNumber('beneficiary');

    // Claims - View only
    Route::get('/claims', [ClaimController::class, 'index'])->name('claims.index');
    Route::get('/claims/{claim}', [ClaimController::class, 'show'])->name('claims.show')->whereNumber('claim');
    Route::get('/claims/{facultyProfile}/beneficiaries', [ClaimController::class, 'getBeneficiaries'])
        ->name('claims.beneficiaries');

    // Salary Deduction Monitoring
    Route::get('/my-salary-deductions', [SalaryDeductionController::class, 'index'])->name('my-salary-deductions');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes - Process Actions (requires processes enabled)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\CheckProcessesEnabled::class])->group(function () {
    // User Profile (Breeze) - Update/Delete
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Beneficiaries - Create/Edit/Delete
    Route::get('/beneficiaries/create', [BeneficiaryController::class, 'create'])->name('beneficiaries.create');
    Route::post('/beneficiaries', [BeneficiaryController::class, 'store'])->name('beneficiaries.store');
    Route::get('/beneficiaries/{beneficiary}/edit', [BeneficiaryController::class, 'edit'])->name('beneficiaries.edit');
    Route::put('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'update'])->name('beneficiaries.update');
    Route::delete('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'destroy'])->name('beneficiaries.destroy');

    // Claims - Create/Edit/Delete
    Route::get('/claims/create', [ClaimController::class, 'create'])->name('claims.create');
    Route::post('/claims', [ClaimController::class, 'store'])->name('claims.store');
    Route::get('/claims/{claim}/edit', [ClaimController::class, 'edit'])->name('claims.edit');
    Route::put('/claims/{claim}', [ClaimController::class, 'update'])->name('claims.update');
    Route::delete('/claims/{claim}', [ClaimController::class, 'destroy'])->name('claims.destroy');

    // Training Registration
    Route::post('/trainings/{trainingSeminar}/register', [TrainingSeminarController::class, 'register'])
        ->name('trainings.register');
    Route::delete('/trainings/{trainingSeminar}/cancel', [TrainingSeminarController::class, 'cancelRegistration'])
        ->name('trainings.cancel-registration');
});

/*
|--------------------------------------------------------------------------
| Pending Registrations Management (Admin & Campus Presidents)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can.manage.registrations'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/pending-registrations', [PendingRegistrationController::class, 'index'])
            ->name('pending-registrations.index');
        Route::get('/pending-registrations/{pendingRegistration}', [PendingRegistrationController::class, 'show'])
            ->name('pending-registrations.show');
        Route::post('/pending-registrations/{pendingRegistration}/approve', [PendingRegistrationController::class, 'approve'])
            ->name('pending-registrations.approve');
        Route::post('/pending-registrations/{pendingRegistration}/reject', [PendingRegistrationController::class, 'reject'])
            ->name('pending-registrations.reject');
    });

/*
|--------------------------------------------------------------------------
| Member & Faculty Profile Management (Admin & Campus Presidents)
|--------------------------------------------------------------------------
| Campus presidents can manage members within their own campus.
| Super admins and university officers can manage all members.
| Authorization is enforced per-record by FacultyProfilePolicy.
*/

Route::middleware(['auth', 'officer:campus_president,university_president,university_treasurer'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Member Registration
        Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
        Route::post('/members', [MemberController::class, 'store'])->name('members.store');
        Route::get('/members/colleges/{campus}', [MemberController::class, 'getColleges'])->name('members.colleges');

        // Faculty Profiles Management
        Route::get('/faculty-profiles/export', [FacultyProfileController::class, 'export'])
            ->name('faculty-profiles.export');
        Route::get('/faculty-profiles/archived', [FacultyProfileController::class, 'archived'])
            ->name('faculty-profiles.archived');
        Route::post('/faculty-profiles/{id}/restore', [FacultyProfileController::class, 'restore'])
            ->name('faculty-profiles.restore');
        Route::post('/faculty-profiles/{facultyProfile}/archive', [FacultyProfileController::class, 'archive'])
            ->name('faculty-profiles.archive');
        Route::post('/faculty-profiles/{facultyProfile}/deactivate', [FacultyProfileController::class, 'deactivate'])
            ->name('faculty-profiles.deactivate');
        Route::post('/faculty-profiles/{facultyProfile}/reactivate', [FacultyProfileController::class, 'reactivate'])
            ->name('faculty-profiles.reactivate');
        Route::resource('faculty-profiles', FacultyProfileController::class);
    });

/*
|--------------------------------------------------------------------------
| Admin Routes (Super Admin Only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // System Settings
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/settings/toggle-registration', [SettingsController::class, 'toggleRegistration'])
            ->name('settings.toggle-registration');
        Route::post('/settings/toggle-processes', [SettingsController::class, 'toggleProcesses'])
            ->name('settings.toggle-processes');
        Route::post('/settings/theme', [SettingsController::class, 'updateTheme'])
            ->name('settings.update-theme');

        // Officer Management
        Route::get('/officers', [OfficerManagementController::class, 'index'])->name('officers.index');
        Route::post('/officers/campus/{campus}/assign', [OfficerManagementController::class, 'assignCampusPresident'])
            ->name('officers.assign-campus-president');
        Route::post('/officers/campus/{campus}/remove', [OfficerManagementController::class, 'removeCampusPresident'])
            ->name('officers.remove-campus-president');
        Route::post('/officers/position/assign', [OfficerManagementController::class, 'assignOfficerPosition'])
            ->name('officers.assign-position');
        Route::post('/officers/position/remove', [OfficerManagementController::class, 'removeOfficerPosition'])
            ->name('officers.remove-position');

        // Claim Types Management
        Route::get('/claim-types', [ClaimTypeController::class, 'index'])->name('claim-types.index');
        Route::get('/claim-types/create', [ClaimTypeController::class, 'create'])->name('claim-types.create');
        Route::post('/claim-types', [ClaimTypeController::class, 'store'])->name('claim-types.store');
        Route::get('/claim-types/{claimType}/edit', [ClaimTypeController::class, 'edit'])->name('claim-types.edit');
        Route::put('/claim-types/{claimType}', [ClaimTypeController::class, 'update'])->name('claim-types.update');
        Route::delete('/claim-types/{claimType}', [ClaimTypeController::class, 'destroy'])->name('claim-types.destroy');

        // Campuses Management
        Route::get('/campuses', [CampusController::class, 'adminIndex'])->name('campuses.index');
        Route::get('/campuses/create', [CampusController::class, 'create'])->name('campuses.create');
        Route::post('/campuses', [CampusController::class, 'store'])->name('campuses.store');
        Route::get('/campuses/{campus}', [CampusController::class, 'adminShow'])->name('campuses.show');
        Route::get('/campuses/{campus}/edit', [CampusController::class, 'edit'])->name('campuses.edit');
        Route::put('/campuses/{campus}', [CampusController::class, 'update'])->name('campuses.update');
        Route::delete('/campuses/{campus}', [CampusController::class, 'destroy'])->name('campuses.destroy');
        Route::get('/campuses/{campus}/statistics', [CampusController::class, 'statistics'])
            ->name('campuses.statistics');

        // Colleges Management (under campuses)
        Route::get('/campuses/{campus}/colleges', [\App\Http\Controllers\Admin\CollegeController::class, 'index'])
            ->name('campuses.colleges');
        Route::get('/campuses/{campus}/colleges/create', [\App\Http\Controllers\Admin\CollegeController::class, 'create'])
            ->name('campuses.colleges.create');
        Route::post('/campuses/{campus}/colleges', [\App\Http\Controllers\Admin\CollegeController::class, 'store'])
            ->name('campuses.colleges.store');
        Route::get('/campuses/{campus}/colleges/{college}/edit', [\App\Http\Controllers\Admin\CollegeController::class, 'edit'])
            ->name('campuses.colleges.edit');
        Route::put('/campuses/{campus}/colleges/{college}', [\App\Http\Controllers\Admin\CollegeController::class, 'update'])
            ->name('campuses.colleges.update');
        Route::delete('/campuses/{campus}/colleges/{college}', [\App\Http\Controllers\Admin\CollegeController::class, 'destroy'])
            ->name('campuses.colleges.destroy');

        // Transparency Posts Management
        Route::get('/transparency', [TransparencyPostController::class, 'adminIndex'])->name('transparency.index');
        Route::get('/transparency/create', [TransparencyPostController::class, 'create'])->name('transparency.create');
        Route::post('/transparency', [TransparencyPostController::class, 'store'])->name('transparency.store');
        Route::get('/transparency/{transparencyPost}/edit', [TransparencyPostController::class, 'edit'])
            ->name('transparency.edit');
        Route::put('/transparency/{transparencyPost}', [TransparencyPostController::class, 'update'])
            ->name('transparency.update');
        Route::delete('/transparency/{transparencyPost}', [TransparencyPostController::class, 'destroy'])
            ->name('transparency.destroy');
        Route::post('/transparency/{transparencyPost}/toggle-publish', [TransparencyPostController::class, 'togglePublish'])
            ->name('transparency.toggle-publish');

        // Trainings Management
        Route::get('/trainings', [TrainingSeminarController::class, 'adminIndex'])->name('trainings.index');
        Route::get('/trainings/create', [TrainingSeminarController::class, 'create'])->name('trainings.create');
        Route::post('/trainings', [TrainingSeminarController::class, 'store'])->name('trainings.store');
        Route::get('/trainings/{trainingSeminar}/edit', [TrainingSeminarController::class, 'edit'])
            ->name('trainings.edit');
        Route::put('/trainings/{trainingSeminar}', [TrainingSeminarController::class, 'update'])
            ->name('trainings.update');
        Route::delete('/trainings/{trainingSeminar}', [TrainingSeminarController::class, 'destroy'])
            ->name('trainings.destroy');
        Route::get('/trainings/{trainingSeminar}/registrations', [TrainingSeminarController::class, 'registrations'])
            ->name('trainings.registrations');
        Route::patch('/registrations/{registration}', [TrainingSeminarController::class, 'updateRegistration'])
            ->name('registrations.update');
    });

/*
|--------------------------------------------------------------------------
| Officer Routes — Claims & Dues (President & Treasurer)
|--------------------------------------------------------------------------
| - Claims approve/reject/remand: Admin + President (manage-claims)
| - Claims release: Admin + Treasurer (release-claims)
| - Dues management: Admin + Treasurer (manage-dues)
*/

Route::middleware(['auth', 'officer'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Claims Monitoring Dashboard
        Route::get('/claims-monitoring', [\App\Http\Controllers\ClaimsMonitoringController::class, 'index'])
            ->name('claims-monitoring');

        // Admin Claim Detail View (separate from faculty "My Claims")
        Route::get('/claims/{claim}', [ClaimController::class, 'adminShow'])
            ->name('claims.show');

        // Claims Management — Start Review/Approve/Reject/Remand (Admin & President)
        Route::post('/claims/{claim}/start-review', [ClaimController::class, 'startReview'])->name('claims.start-review');
        Route::post('/claims/{claim}/approve', [ClaimController::class, 'approve'])->name('claims.approve');
        Route::post('/claims/{claim}/reject', [ClaimController::class, 'reject'])->name('claims.reject');
        Route::post('/claims/{claim}/remand', [ClaimController::class, 'remand'])->name('claims.remand');

        // Claims Billing & Collection Workflow (Admin & Treasurer)
        Route::post('/claims/{claim}/prepare-billing', [ClaimController::class, 'prepareBilling'])->name('claims.prepare-billing');
        Route::post('/claims/{claim}/start-collecting', [ClaimController::class, 'startCollecting'])->name('claims.start-collecting');
        Route::post('/claims/{claim}/prepare-check', [ClaimController::class, 'prepareCheck'])->name('claims.prepare-check');

        // Claims Release (Admin & Treasurer)
        Route::post('/claims/{claim}/release', [ClaimController::class, 'release'])->name('claims.release');

        // Deductions Collection — Salary Deductions (Admin & Treasurer)
        Route::get('/dues', [DueController::class, 'index'])->name('dues.index');
        Route::post('/dues/generate', [DueController::class, 'generate'])->name('dues.generate');
        Route::post('/dues/{deduction}/mark-collected', [DueController::class, 'markCollected'])->name('dues.mark-collected');
        Route::post('/dues/mark-campus-collected', [DueController::class, 'markCampusCollected'])->name('dues.mark-campus-collected');
        Route::get('/dues/export', [DueController::class, 'export'])->name('dues.export');

        // Claim Deduction Settings (Admin & Treasurer)
        Route::get('/deductions', [DeductionSettingController::class, 'index'])->name('deductions.index');
        Route::put('/deductions', [DeductionSettingController::class, 'update'])->name('deductions.update');
        Route::put('/deductions/{academicRank}', [DeductionSettingController::class, 'updateSingle'])
            ->name('deductions.update-single');

        // Fund Monitoring (Admin & Treasurer)
        Route::get('/fund', [FundController::class, 'index'])->name('fund.index');
        Route::post('/fund/transactions', [FundController::class, 'storeTransaction'])->name('fund.store-transaction');
        Route::get('/fund/export', [FundController::class, 'export'])->name('fund.export');

        // Monthly Salary Deductions (Admin & Treasurer)
        Route::get('/monthly-deductions', [MonthlyDeductionController::class, 'index'])->name('monthly-deductions.index');
        Route::post('/monthly-deductions/generate', [MonthlyDeductionController::class, 'generate'])->name('monthly-deductions.generate');
        Route::post('/monthly-deductions/{monthlyDeduction}/mark-collected', [MonthlyDeductionController::class, 'markCollected'])->name('monthly-deductions.mark-collected');
        Route::post('/monthly-deductions/mark-campus-collected', [MonthlyDeductionController::class, 'markCampusCollected'])->name('monthly-deductions.mark-campus-collected');
        Route::get('/monthly-deductions/export', [MonthlyDeductionController::class, 'export'])->name('monthly-deductions.export');
    });

/*
|--------------------------------------------------------------------------
| Transparency Wall Module (Admin Only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Transparency Dashboard
        Route::get('/transparency-wall', [PageController::class, 'transparencyDashboard'])
            ->name('transparency.dashboard');

        // Memos CRUD
        Route::resource('transparency/memos', MemoController::class)->names([
            'index' => 'transparency.memos.index',
            'create' => 'transparency.memos.create',
            'store' => 'transparency.memos.store',
            'show' => 'transparency.memos.show',
            'edit' => 'transparency.memos.edit',
            'update' => 'transparency.memos.update',
            'destroy' => 'transparency.memos.destroy',
        ]);
        Route::post('/transparency/memos/{memo}/toggle-publish', [MemoController::class, 'togglePublish'])
            ->name('transparency.memos.toggle-publish');
        Route::delete('/transparency/memos/{memo}/attachment', [MemoController::class, 'removeAttachment'])
            ->name('transparency.memos.remove-attachment');

        // Resolutions CRUD
        Route::resource('transparency/resolutions', ResolutionController::class)->names([
            'index' => 'transparency.resolutions.index',
            'create' => 'transparency.resolutions.create',
            'store' => 'transparency.resolutions.store',
            'show' => 'transparency.resolutions.show',
            'edit' => 'transparency.resolutions.edit',
            'update' => 'transparency.resolutions.update',
            'destroy' => 'transparency.resolutions.destroy',
        ]);
        Route::post('/transparency/resolutions/{resolution}/toggle-publish', [ResolutionController::class, 'togglePublish'])
            ->name('transparency.resolutions.toggle-publish');
        Route::delete('/transparency/resolutions/{resolution}/attachment', [ResolutionController::class, 'removeAttachment'])
            ->name('transparency.resolutions.remove-attachment');

        // Collections CRUD
        Route::resource('transparency/collections', CollectionController::class)->names([
            'index' => 'transparency.collections.index',
            'create' => 'transparency.collections.create',
            'store' => 'transparency.collections.store',
            'show' => 'transparency.collections.show',
            'edit' => 'transparency.collections.edit',
            'update' => 'transparency.collections.update',
            'destroy' => 'transparency.collections.destroy',
        ]);
        Route::post('/transparency/collections/{collection}/toggle-publish', [CollectionController::class, 'togglePublish'])
            ->name('transparency.collections.toggle-publish');
        Route::delete('/transparency/collections/{collection}/attachment', [CollectionController::class, 'removeAttachment'])
            ->name('transparency.collections.remove-attachment');

        // Releases CRUD
        Route::resource('transparency/releases', ReleaseController::class)->names([
            'index' => 'transparency.releases.index',
            'create' => 'transparency.releases.create',
            'store' => 'transparency.releases.store',
            'show' => 'transparency.releases.show',
            'edit' => 'transparency.releases.edit',
            'update' => 'transparency.releases.update',
            'destroy' => 'transparency.releases.destroy',
        ]);
        Route::post('/transparency/releases/{release}/toggle-publish', [ReleaseController::class, 'togglePublish'])
            ->name('transparency.releases.toggle-publish');
        Route::delete('/transparency/releases/{release}/attachment', [ReleaseController::class, 'removeAttachment'])
            ->name('transparency.releases.remove-attachment');

        // Training Announcements CRUD
        Route::resource('transparency/trainings', TrainingAnnouncementController::class)->names([
            'index' => 'transparency.trainings.index',
            'create' => 'transparency.trainings.create',
            'store' => 'transparency.trainings.store',
            'show' => 'transparency.trainings.show',
            'edit' => 'transparency.trainings.edit',
            'update' => 'transparency.trainings.update',
            'destroy' => 'transparency.trainings.destroy',
        ]);
        Route::post('/transparency/trainings/{training}/update-status', [TrainingAnnouncementController::class, 'updateStatus'])
            ->name('transparency.trainings.update-status');
        Route::delete('/transparency/trainings/{training}/attachment', [TrainingAnnouncementController::class, 'removeAttachment'])
            ->name('transparency.trainings.remove-attachment');
    });

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Philippine Address API Routes (public, no auth required)
|--------------------------------------------------------------------------
*/
Route::prefix('api/ph-address')->group(function () {
    Route::get('/provinces', [PhAddressController::class, 'provinces'])->name('api.ph.provinces');
    Route::get('/provinces/{province}/cities', [PhAddressController::class, 'cities'])->name('api.ph.cities');
    Route::get('/cities/{city}/barangays', [PhAddressController::class, 'barangays'])->name('api.ph.barangays');
});
