<?php

namespace Tests\Feature\Auth;

use App\Models\Campus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1x1 transparent PNG (valid image bytes) for required uploads, so tests
     * don't depend on the GD extension being available.
     */
    private const PIXEL_PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';

    public function test_registration_screen_can_be_rendered(): void
    {
        Campus::create(['name' => 'Main Campus', 'code' => 'MC']);

        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Storage::fake('public');

        $campus = Campus::create(['name' => 'Main Campus', 'code' => 'MC']);

        $image = fn () => UploadedFile::fake()
            ->createWithContent('image.png', base64_decode(self::PIXEL_PNG));

        $response = $this->post('/register', [
            'surname' => 'Doe',
            'first_name' => 'Jane',
            'middle_name' => 'Quill',
            'civil_status' => 'single',
            'gender' => 'female',
            'birth_date' => '1990-01-01',
            'birth_place' => 'Manila',
            'home_address' => '123 Sample Street',
            'address_province' => 'Metro Manila',
            'address_city' => 'Manila',
            'address_barangay' => 'Barangay Uno',
            'phone_number' => '09171234567',
            'campus_id' => $campus->id,
            'id_picture' => $image(),
            'employee_id_front' => $image(),
            'employee_id_back' => $image(),
            'employee_id' => 'EMP-00001',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('pending_registrations', [
            'surname' => 'Doe',
            'first_name' => 'Jane',
            'employee_id_number' => 'EMP-00001',
            'campus_id' => $campus->id,
            'status' => 'pending',
        ]);

        $this->assertDatabaseCount('users', 0);
    }
}
