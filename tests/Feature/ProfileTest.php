<?php

namespace Tests\Feature;

use App\Models\Campus;
use App\Models\FacultyProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $campus = Campus::create(['name' => 'Main Campus', 'code' => 'MC']);

        $user = User::factory()->create();

        $profile = FacultyProfile::create([
            'user_id' => $user->id,
            'surname' => 'Smith',
            'first_name' => 'John',
            'civil_status' => 'single',
            'gender' => 'male',
            'birth_date' => '1985-05-05',
            'birth_place' => 'Cebu',
            'home_address' => 'Original Address',
            'phone_number' => '09170000000',
            'employee_id' => $user->employee_id,
            'campus_id' => $campus->id,
            'membership_status' => 'active',
        ]);

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'surname' => 'Doe',
                'first_name' => 'Jane',
                'middle_name' => 'Quill',
                'civil_status' => 'married',
                'gender' => 'female',
                'birth_date' => '1990-02-02',
                'birth_place' => 'Manila',
                'home_address' => 'New Address',
                'address_province' => 'Metro Manila',
                'address_city' => 'Manila',
                'address_barangay' => 'Barangay Uno',
                'phone_number' => '09171234567',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('profile.edit'));

        $profile->refresh();

        $this->assertSame('Doe', $profile->surname);
        $this->assertSame('Jane', $profile->first_name);
        $this->assertSame('married', $profile->civil_status);
        $this->assertSame('New Address', $profile->home_address);
        $this->assertSame('09171234567', $profile->phone_number);

        $user->refresh();
        $this->assertSame('Jane Q. Doe', $user->name);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
