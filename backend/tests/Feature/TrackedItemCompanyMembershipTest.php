<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Company;
use App\Models\TrackedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackedItemCompanyMembershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracked_item_responsible_user_must_belong_to_company(): void
    {
        $company = Company::create([
            'name' => 'ABC Sdn Bhd',
            'slug' => 'abc-sdn-bhd',
            'timezone' => 'Asia/Kuala_Lumpur',
            'locale' => 'en_MY',
            'subscription_status' => 'trial',
        ]);

        $category = Category::create([
            'company_id' => $company->id,
            'name' => 'Insurance',
            'group_name' => 'Company',
            'is_default' => true,
            'sort_order' => 1,
        ]);

        $owner = User::factory()->create();
        $company->users()->attach($owner->id, [
            'role' => 'owner',
            'permissions' => json_encode(['*']),
            'status' => 'active',
            'is_primary' => true,
        ]);

        $externalUser = User::factory()->create();

        $this->actingAs($owner)
            ->postJson('/api/tracked-items', [
                'category_id' => $category->id,
                'name' => 'Company Insurance',
                'responsible_user_id' => $externalUser->id,
                'expiry_date' => '2026-09-30',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['responsible_user_id']);
    }
}
