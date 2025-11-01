<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssetControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_create_and_store()
    {
        Asset::factory()->count(2)->create();

        $res = $this->actingAs($this->user)->get(route('asset.index'));
        $res->assertStatus(200);

        $res2 = $this->actingAs($this->user)->get(route('asset.create'));
        $res2->assertStatus(200);

        $payload = ['name' => 'Laptop', 'value' => 1500, 'acquired_at' => now()->format('Y-m-d')];
        $r = $this->actingAs($this->user)->post(route('assets.store'), $payload);
        $r->assertRedirect(route('asset.index'));
        $this->assertDatabaseHas('asset', ['asset_name' => 'Laptop']);
    }
}
