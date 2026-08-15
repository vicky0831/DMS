<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationSeedsCategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_a_company_and_default_categories(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Owner Admin',
            'email' => 'owner@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'company_name' => 'ABC Sdn Bhd',
            'company_registration_number' => '202601234567',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('companies', [
            'name' => 'ABC Sdn Bhd',
            'slug' => 'abc-sdn-bhd',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'SSM',
            'group_name' => 'Company',
            'is_default' => true,
        ]);

        $this->assertDatabaseHas('company_user', [
            'role' => 'owner',
            'status' => 'active',
            'is_primary' => true,
        ]);
    }
}
