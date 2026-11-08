<?php

namespace Tests\Feature;

use App\Models\Campus;
use App\Models\Claim;
use App\Models\ClaimType;
use App\Models\FacultyProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityFixesTest extends TestCase
{
    use RefreshDatabase;

    private function makeProfile(User $user, Campus $campus): FacultyProfile
    {
        return FacultyProfile::create([
            'user_id' => $user->id,
            'surname' => 'Smith',
            'first_name' => 'John',
            'civil_status' => 'single',
            'gender' => 'male',
            'birth_date' => '1985-05-05',
            'birth_place' => 'Cebu',
            'home_address' => '123 Main St',
            'phone_number' => '09170000000',
            'employee_id' => $user->employee_id,
            'campus_id' => $campus->id,
            'membership_status' => 'active',
        ]);
    }

    public function test_non_super_admin_cannot_create_super_admin_member(): void
    {
        $campus = Campus::create(['name' => 'Main Campus', 'code' => 'MC']);
        $president = User::factory()->create([
            'role' => 'university_president',
            'campus_id' => $campus->id,
        ]);

        $response = $this->actingAs($president)->post('/admin/members', [
            'prefix' => '',
            'surname' => 'Doe',
            'first_name' => 'Jane',
            'middle_name' => '',
            'extension_name' => '',
            'civil_status' => 'single',
            'gender' => 'female',
            'birth_date' => '1990-01-01',
            'birth_place' => 'Manila',
            'home_address' => '123 Main St',
            'phone_number' => '09171234567',
            'employee_id' => 'EMP-ESC-001',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'super_admin',
            'campus_id' => $campus->id,
            'membership_status' => 'active',
        ]);

        $response->assertRedirect(route('admin.faculty-profiles.index'));

        $this->assertSame(
            'faculty',
            User::where('employee_id', 'EMP-ESC-001')->firstOrFail()->role
        );
    }

    public function test_campus_president_cannot_add_beneficiary_for_another_campus(): void
    {
        $campusA = Campus::create(['name' => 'Campus A', 'code' => 'CA']);
        $campusB = Campus::create(['name' => 'Campus B', 'code' => 'CB']);

        $president = User::factory()->create([
            'role' => 'campus_president',
            'campus_id' => $campusA->id,
        ]);

        $member = User::factory()->create();
        $profile = $this->makeProfile($member, $campusB);

        $response = $this->actingAs($president)->post('/beneficiaries', [
            'faculty_profile_id' => $profile->id,
            'full_name' => 'Out of Campus Beneficiary',
            'relationship' => 'mother',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('beneficiaries', [
            'full_name' => 'Out of Campus Beneficiary',
        ]);
    }

    public function test_campus_president_cannot_manage_deduction_settings(): void
    {
        $campus = Campus::create(['name' => 'Main Campus', 'code' => 'MC']);
        $president = User::factory()->create([
            'role' => 'campus_president',
            'campus_id' => $campus->id,
        ]);

        $this->actingAs($president)->get('/admin/deductions')->assertForbidden();
    }

    public function test_faculty_without_profile_does_not_see_other_claims(): void
    {
        $campus = Campus::create(['name' => 'Main Campus', 'code' => 'MC']);

        $owner = User::factory()->create();
        $profile = $this->makeProfile($owner, $campus);

        $claimType = ClaimType::create([
            'name' => 'Medical',
            'code' => 'MED',
            'max_amount' => 10000,
            'is_active' => true,
        ]);

        $claim = Claim::create([
            'faculty_profile_id' => $profile->id,
            'claim_type_id' => $claimType->id,
            'amount' => 1000,
            'deduction_amount' => 0,
            'net_amount' => 1000,
            'date_applied' => now()->toDateString(),
            'status' => 'pending',
        ]);

        $viewer = User::factory()->create(['role' => 'faculty']);

        $response = $this->actingAs($viewer)->get('/claims');

        $response->assertOk();
        $response->assertDontSee($claim->claim_number);
    }
}
