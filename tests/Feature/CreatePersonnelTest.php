<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Personnel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreatePersonnelTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_personnel_does_not_persist_in_db()
    {
        // Fake the storage for file uploads
        Storage::fake('public');

        // Create a business and associate it with the user
        $business = \App\Models\businesses::factory()->create();
        $user = User::factory()->create([
            'business_id' => $business->id,
            // 'password' => 'test123',
        ]);
        $this->actingAs($user);

        $payload = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'othername' => 'Middle',
            'email' => 'john.doe@example.com',
            'designation' => 'Manager',
            'marital_status' => 'Single',
            'employment_date' => '2024-01-01',
            'category' => 'staff',
            'department' => 'HR',
            'phone_number' => '08012345678',
            'picture' => UploadedFile::fake()->image('avatar.jpg'), // Uncomment if testing file upload
            'cv' => UploadedFile::fake()->create('cv.pdf', 100),    // Uncomment if testing file upload
            'state_of_origin' => 'Lagos',
            'address' => '123 Main St',
            'salary' => 50000,
            'highest_certificate' => 'BSc',
            'dob' => '1990-01-01',
            'nationality' => 'Nigerian',
            'password' => 'password123', // Required for User creation
            'role' => 2, // Use a valid role ID from your roles table (not Super Admin or Client)
        ];

        $response = $this->post(route('create.personnel'), $payload);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('users', ['email' => 'john.doe@example.com',]);
        $this->assertDatabaseHas('personnels', ['firstname' => 'John', 'email' => 'john.doe@example.com']);
    }
}
