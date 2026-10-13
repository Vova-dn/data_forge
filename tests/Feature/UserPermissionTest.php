<?php

namespace Tests\Feature;

use App\Models\Dataset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserPermissionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_user_check_own_dataset(): void
    {
        $owner = User::factory()->create();
        $dataset = Dataset::factory()->create([
           'owner_id' => $owner->id
        ]);

        $response = $this->actingAs($owner)->get('/datasets/' . $dataset->id);

        $response->assertOk()
            ->assertViewIs('datasets')
            ->assertViewHas('dataset', $dataset);

    }
    public function test_user_check_not_own_dataset(): void
    {
        $owner = User::factory()->create();
        $notOwner = User::factory()->create();
        $dataset = Dataset::factory()->create([
            'owner_id' => $owner->id
        ]);

        $response = $this->actingAs($notOwner)->get('/datasets/' . $dataset->id);

        $response->assertForbidden();
    }
    public function test_guest_check_someone_dataset(): void{
        $owner = User::factory()->create();
        $dataset = Dataset::factory()->create([
            'owner_id' => $owner->id
        ]);

        $response = $this->get('/datasets/' . $dataset->id);
        $response->assertForbidden();
    }

}
