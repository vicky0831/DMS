<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use App\Models\TrackedItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_other_company_items(): void
    {
        $company1 = Company::create(['name' => 'Company A', 'slug' => 'company-a']);
        $user1 = User::create(['name' => 'User A', 'email' => 'usera@example.com', 'password' => Hash::make('password')]);
        $company1->users()->attach($user1->id, ['role' => 'owner', 'permissions' => json_encode(['*'])]);

        $company2 = Company::create(['name' => 'Company B', 'slug' => 'company-b']);
        $user2 = User::create(['name' => 'User B', 'email' => 'userb@example.com', 'password' => Hash::make('password')]);
        $company2->users()->attach($user2->id, ['role' => 'owner', 'permissions' => json_encode(['*'])]);

        $item2 = TrackedItem::create([
            'company_id' => $company2->id,
            'owner_user_id' => $user2->id,
            'name' => 'Item B',
            'status' => 'active',
        ]);

        $response = $this->actingAs($user1)->getJson("/api/items/{$item2->id}");
        $response->assertStatus(403);
    }
}
